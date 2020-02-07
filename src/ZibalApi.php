<?php


namespace Zibal;

class ZibalApi
{

    private $errors = [
        -1 => 'در انتظار پردخت',
        -2 => 'خطای داخلی',
        1 => 'پرداخت شده - تاییدشده',
        2 => 'پرداخت شده - تاییدنشده',
        3 => 'لغوشده توسط کاربر',
        4 => '‌شماره کارت نامعتبر می‌باشد.',
        5 => '‌موجودی حساب کافی نمی‌باشد.',
        6 => 'رمز واردشده اشتباه می‌باشد.',
        7 => '‌تعداد درخواست‌ها بیش از حد مجاز می‌باشد.',
        8 => '‌تعداد پرداخت اینترنتی روزانه بیش از حد مجاز می‌باشد',
        9 => 'مبلغ پرداخت اینترنتی روزانه بیش از حد مجاز می‌باشد',
        10 => '‌صادرکننده‌ی کارت نامعتبر می‌باشد',
        11 => '‌خطای سوییچ',
        12 => 'کارت قابل دسترسی نمی‌باشد',
        100 => 'با موفقیت تایید شد',
        102 => 'merchant یافت نشد.',
        103 => 'merchant غیرفعال',
        104 => 'merchant نامعتبر',
        201 => 'قبلا تایید شده',
        202 => 'لغو شده توسط کاربر',
        105 => 'amount بایستی بزرگتر از 1,000 ریال باشد.',
        106 => 'callbackUrl نامعتبر می‌باشد. (شروع با http و یا https)',
        113 => 'amount مبلغ تراکنش از سقف میزان تراکنش بیشتر است.'
    ];
    /**
     * @var Client
     */
    private $client;
    /**
     * @var integer
     */
    private static $trackId;

    /**
     * Zibal constructor.
     */
    public function __construct()
    {
        $this->client = new Client;
    }

    /**
     * @param int $trackId
     */
    public function setTrackId(int $trackId): void
    {
        static::$trackId = $trackId;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function request(array $data = [])
    {
        $response = $this->client->request($data);
        if ($response->hasMessage() && $response->getMessage() === 'success') {
            $trackId = $response->getTrackId();
            $gateway = new Gateway();
            $gateway->amount = $data['amount'];
            $gateway->description = $data['description'] ?? null;
            $gateway->order_id = $data['order_id'] ?? null;
            $gateway->mobile = $data['mobile'] ?? null;
            $gateway->track_id = $trackId;
            $gateway->status = 2;
            $gateway->save();
            $this->setTrackId($trackId);
            return [
                'status'=> true,
                'trackId'=> $trackId
            ];
        }
        return [
            'status' => false,
            'message' => $this->errors[$response->getResult()] ?? $response->getMessage()
        ];
    }

    /**
     * @param void $trackId
     * @return array
     */
    protected function verify($trackId = null)
    {
        $trackId = is_null($trackId)?request('trackId'):$trackId;
        $response = $this->client->verify(compact('trackId'));
        if ($response->hasMessage() && $response->getMessage() === 'success') {
            $gateway = new Gateway();
            $query = $gateway->newQuery();
            $gateway = $query->where('track_id', $trackId)->first();
            $gateway->description = $response->getDescrption();
            $gateway->order_id = $response->getOrderId();
            $gateway->track_id = $trackId;
            $gateway->status = 1;
            $gateway->update();
            $this->setTrackId($trackId);
            return [
                'status' => true,
                'message' => $this->errors[$response->getResult()] ?? $response->getMessage()
            ];
        }
        return [
            'status' => false,
            'message' => $this->errors[$response->getResult()] ?? $response->getMessage()
        ];
    }

    /**
     * @param null $trackId
     * @param bool $directly
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|false
     */
    protected function redirect($trackId = null, $directly = false)
    {
        $trackId = empty($trackId) ? static::$trackId : $trackId;
        if (empty($trackId))
            return false;
        return redirect('https://gateway.zibal.ir/start/' . $trackId . ($directly ? '/direct' : null));
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return call_user_func_array([$this, $method], $arguments);
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments = [])
    {
        return call_user_func_array([new static, $method], $arguments);
    }

}

<?php


namespace Zibal;

use stdClass;

class Client
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var stdClass
     */
    private $response;
    /**
     * @var array
     */
    private $authenticate;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->authenticate['AccountID'] = config('perfectmoney.account_id');
        $this->authenticate['PassPhrase'] = config('perfectmoney.password');
        $this->response = new Response;
    }

    /**
     * @param array $data
     * @return Response|stdClass
     */
    protected function request(array $data = []):Response
    {
        return $this->response->setMessage($this->request->post('request', array_merge(['merchant'=> $this->merchant], $data)));
    }

    /**
     * @param array $data
     * @return Response|stdClass
     */
    protected function verify(array $data = []):Response
    {
        return $this->response->setMessage($this->request->post('verify', array_merge(['merchant'=> $this->merchant], $data)));
    }
    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return call_user_func_array([new $this, $method], $arguments);
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

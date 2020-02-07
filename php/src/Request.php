<?php


namespace Zibal;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class Request
 * @package Jey\Zibal
 */
class Request
{

    /**
     *
     */
    const GATEWAY_URI = 'https://gateway.zibal.ir/%s/';
    /**
     * @var \Illuminate\Config\Repository
     */
    private $version;
    /**
     * @var string
     */
    private $base_uri;

    /**
     * Request constructor.
     * @param string $protocol
     */
    public function __construct()
    {
        $this->version = config('zibal.version', 'v1');
        $this->base_uri = sprintf(static::GATEWAY_URI, $this->version);
    }

    /**
     * @param $action
     * @param array $json
     * @return mixed
     */
    public function post($action, $json = [])
    {
        $client = new Client(['base_uri'=> $this->base_uri]);
        $promise = $client->postAsync($action, compact('json'))->then(
            [$this, 'onFulfilled'],
            [$this, 'onRejected']
        );
        return $promise->wait();
    }


    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function onFulfilled(ResponseInterface $response) {
        return $response->getBody()->getContents();
    }

    /**
     * @param Throwable $exception
     * @return mixed
     */
    private function onRejected(Throwable $exception)
    {
        return $exception->getResponse()->getBody()->getContents();
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

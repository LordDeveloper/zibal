<?php


namespace Zibal;


class Response
{
    /**
     * @var string
     */
    public $message;

    /**
     * @return array|mixed|string
     */
    public function toArray()
    {
        if(! empty($this->message) && $this->isJson())
            return json_decode($this->message, true);

        if(is_array($this->message))
            return $this->message;

    }

    /**
     * @return object|mixed|string
     */
    public function toObject()
    {
        if(! empty($this->message) && $this->isJson())
            return json_decode($this->message, false);

        if(is_object($this->message))
            return $this->message;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isJson()
    {
        return @json_decode($this->message) && json_last_error() === 0;
    }

    /**
     * @param $name
     * @param array $arguments
     * @return bool
     */
    public function __call($name, $arguments = [])
    {
       if(strpos($name, 'get') === 0) {
            $property = lcfirst(substr($name, 3));
            if(!isset($this->toObject()->{$property}))
                return null;
            $object = $this->toObject()->{$property};
            if(is_object($object)){
                $instance = new Response();
                $instance->setMessage($object);
                return $this;
            }

            return $object;
       }
        if(strpos($name, 'has') === 0) {
            $property = lcfirst(substr($name, 3));
            return isset($this->toObject()->{$property});
        }
    }

    /**
     * @param string $message
     * @return Response
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }


}

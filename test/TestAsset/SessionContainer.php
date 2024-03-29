<?php

namespace LaminasTest\Captcha\TestAsset;

class SessionContainer
{
    protected static $word;

    public function __get($name)
    {
        if ('word' == $name) {
            return static::$word;
        }

        return null;
    }

    public function __set($name, $value)
    {
        if ('word' == $name) {
            static::$word = $value;
        } else {
            $this->$name = $value;
        }
    }

    public function __isset($name)
    {
        if (('word' == $name) && (null !== static::$word)) {
            return true;
        }

        return false;
    }

    public function __call($method, $args)
    {
        switch ($method) {
            case 'setExpirationHops':
            case 'setExpirationSeconds':
                $this->$method = array_shift($args);
                break;
            default:
        }
    }
}

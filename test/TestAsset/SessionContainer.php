<?php

namespace LaminasTest\Captcha\TestAsset;

/**
 * @final This class should not be extended
 */
class SessionContainer
{
    protected static $word;
    
    protected $data = [];

    public function __isset($name)
    {
        if (('word' == $name) && (null !== static::$word)) {
            return true;
        }

        return false;
    }
    
    public function __get($name)
    {
        if ($name === 'word') {
            return static::$word;
        }
        return $this->data[$name] ?? null;
    }
    
    public function __set($name, $value)
    {
        if ($name === 'word') {
            static::$word = $value;
        } else {
            $this->data[$name] = $value;
        }
    }
    
    public function setExpirationHops($hops, $namespace = null): void
    {
        $this->data['setExpirationHops'] = $hops;
    }
    
    public function setExpirationSeconds($seconds): void
    {
        $this->data['setExpirationSeconds'] = $seconds;
    }
}

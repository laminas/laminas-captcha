<?php

namespace LaminasTest\Captcha\TestAsset;

class SessionContainer
{
    protected static $word;

    public function __isset($name)
    {
        if (('word' == $name) && (null !== static::$word)) {
            return true;
        }

        return false;
    }
}

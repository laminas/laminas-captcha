<?php

/**
 * @see       https://github.com/laminas/laminas-captcha for the canonical source repository
 * @copyright https://github.com/laminas/laminas-captcha/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-captcha/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Captcha\TestAsset;

use Laminas\Captcha\AdapterInterface;

class MockCaptcha implements AdapterInterface
{
    public $name;
    public $options = [];

    public function __construct($options = null)
    {
        $this->options = $options;
    }

    public function generate()
    {
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHelperName()
    {
        return 'doctype';
    }

    public function isValid($value)
    {
        return true;
    }

    public function getMessages()
    {
        return [];
    }
}

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
    /** @var null|string */
    public $name;

    /** @var null|array */
    public $options = [];

    public function __construct(array $options = null)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return '';
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name ?: '';
    }

    /**
     * @return string
     */
    public function getHelperName()
    {
        return 'doctype';
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return true;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return [];
    }
}

<?php

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

    /** @inheritDoc */
    public function generate()
    {
        return '';
    }

    /** @inheritDoc */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /** @inheritDoc */
    public function getName()
    {
        return $this->name ?: '';
    }

    /** @inheritDoc */
    public function getHelperName()
    {
        return 'doctype';
    }

    /** @inheritDoc */
    public function isValid($value)
    {
        return true;
    }

    /** @inheritDoc */
    public function getMessages()
    {
        return [];
    }
}

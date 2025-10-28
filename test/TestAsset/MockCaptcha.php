final <?php

namespace LaminasTest\Captcha\TestAsset;

use Laminas\Captcha\AdapterInterface;

class MockCaptcha implements AdapterInterface
{
    /** @var null|string */
    public $name;

    /** @var null|array */
    public $options = [];

    /** @inheritDoc */
    #[\Override]
    public function generate()
    {
        return '';
    }

    /** @inheritDoc */
    #[\Override]
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /** @inheritDoc */
    #[\Override]
    public function getName()
    {
        return $this->name ?: '';
    }

    /** @inheritDoc */
    #[\Override]
    public function getHelperName()
    {
        return 'doctype';
    }

    /** @inheritDoc */
    #[\Override]
    public function isValid($value)
    {
        return true;
    }

    /** @inheritDoc */
    #[\Override]
    public function getMessages()
    {
        return [];
    }
}

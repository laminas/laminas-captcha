<?php

declare(strict_types=1);

namespace Laminas\Captcha;

use Laminas\Text\Figlet\Figlet as FigletManager;
use Override;

/**
 * Captcha based on figlet text rendering service
 *
 * Note that this engine seems not to like numbers
 *
 * @final This class should not be extended
 */
class Figlet extends AbstractWord
{
    /**
     * Figlet text renderer
     *
     * @var FigletManager
     */
    protected $figlet;

    /**
     * Constructor
     *
     * @param null|iterable<string, mixed> $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->figlet = new FigletManager($options);
    }

    /**
     * Retrieve the composed figlet manager
     *
     * @return FigletManager
     */
    public function getFiglet()
    {
        return $this->figlet;
    }

    /**
     * Generate new captcha
     *
     * @return string
     */
    #[Override]
    public function generate()
    {
        $this->useNumbers = false;
        return parent::generate();
    }

    /**
     * Get helper name used to render captcha
     *
     * @return string
     */
    #[Override]
    public function getHelperName()
    {
        return 'captcha/figlet';
    }
}

<?php

declare(strict_types=1);

namespace Laminas\Captcha;

use Override;

/**
 * Example dumb word-based captcha
 *
 * Note that only rendering is necessary for word-based captcha
 *
 * @todo This likely needs its own validation since it expects the final word
 *     entered to be the strrev of the word stored.
 * @final This class should not be extended
 */
class Dumb extends AbstractWord
{
    /**
     * CAPTCHA label
     *
     * @var string
     */
    protected $label = 'Please type this word backwards';

    /**
     * Set the label for the CAPTCHA
     *
     * @param string $label
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Retrieve the label for the CAPTCHA
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Retrieve optional view helper name to use when rendering this captcha
     *
     * @return string
     */
    #[Override]
    public function getHelperName()
    {
        return 'captcha/dumb';
    }
}

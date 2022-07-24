<?php

declare(strict_types=1);

namespace LaminasTest\Captcha;

use Laminas\Captcha\Dumb as DumbCaptcha;
use LaminasTest\Captcha\TestAsset\SessionContainer;

use function strlen;

/**
 * @group      Laminas_Captcha
 */
class DumbTest extends CommonWordTest
{
    /** @var string */
    protected $wordClass = DumbCaptcha::class;

    /** @var DumbCaptcha */
    protected $captcha;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp(): void
    {
        if (isset($this->word)) {
            unset($this->word);
        }

        $this->captcha = new DumbCaptcha([
            'sessionClass' => SessionContainer::class,
        ]);
    }

    public function testUsesCaptchaDumbAsHelper(): void
    {
        $this->assertEquals('captcha/dumb', $this->captcha->getHelperName());
    }

    public function testGeneratePopulatesId(): void
    {
        $id   = $this->captcha->generate();
        $test = $this->captcha->getId();
        $this->assertEquals($id, $test);
    }

    public function testGeneratePopulatesSessionWithWord(): void
    {
        $this->captcha->generate();
        $word    = $this->captcha->getWord();
        $session = $this->captcha->getSession();
        $this->assertEquals($word, $session->word);
    }

    public function testGenerateWillNotUseNumbersIfUseNumbersIsDisabled(): void
    {
        $this->captcha->setUseNumbers(false);
        $this->captcha->generate();
        $word = $this->captcha->getWord();
        $this->assertDoesNotMatchRegularExpression('/\d/', $word);
    }

    public function testWordIsExactlyAsLongAsWordLen(): void
    {
        $this->captcha->setWordLen(10);
        $this->captcha->generate();
        $word = $this->captcha->getWord();
        $this->assertEquals(10, strlen($word));
    }

    /**
     * @group Laminas-11522
     */
    public function testDefaultLabelIsUsedWhenNoAlternateLabelSet(): void
    {
        $this->assertEquals('Please type this word backwards', $this->captcha->getLabel());
    }

    /**
     * @group Laminas-11522
     */
    public function testChangeLabelViaSetterMethod(): void
    {
        $this->captcha->setLabel('Testing');
        $this->assertEquals('Testing', $this->captcha->getLabel());
    }
}

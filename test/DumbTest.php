<?php

/**
 * @see       https://github.com/laminas/laminas-captcha for the canonical source repository
 * @copyright https://github.com/laminas/laminas-captcha/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-captcha/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Captcha;

use Laminas\Captcha\Dumb as DumbCaptcha;

/**
 * @group      Laminas_Captcha
 */
class DumbTest extends CommonWordTest
{
    protected $wordClass = 'Laminas\Captcha\Dumb';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        if (isset($this->word)) {
            unset($this->word);
        }

        $this->captcha = new DumbCaptcha([
            'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
        ]);
    }

    public function testUsesCaptchaDumbAsHelper()
    {
        $this->assertEquals('captcha/dumb', $this->captcha->getHelperName());
    }

    public function testGeneratePopulatesId()
    {
        $id   = $this->captcha->generate();
        $test = $this->captcha->getId();
        $this->assertEquals($id, $test);
    }

    public function testGeneratePopulatesSessionWithWord()
    {
        $this->captcha->generate();
        $word    = $this->captcha->getWord();
        $session = $this->captcha->getSession();
        $this->assertEquals($word, $session->word);
    }

    public function testGenerateWillNotUseNumbersIfUseNumbersIsDisabled()
    {
        $this->captcha->setUseNumbers(false);
        $this->captcha->generate();
        $word = $this->captcha->getWord();
        $this->assertNotRegexp('/\d/', $word);
    }

    public function testWordIsExactlyAsLongAsWordLen()
    {
        $this->captcha->setWordLen(10);
        $this->captcha->generate();
        $word = $this->captcha->getWord();
        $this->assertEquals(10, strlen($word));
    }

    /**
     * @group Laminas-11522
     */
    public function testDefaultLabelIsUsedWhenNoAlternateLabelSet()
    {
        $this->assertEquals('Please type this word backwards', $this->captcha->getLabel());
    }

    /**
     * @group Laminas-11522
     */
    public function testChangeLabelViaSetterMethod()
    {
        $this->captcha->setLabel('Testing');
        $this->assertEquals('Testing', $this->captcha->getLabel());
    }
}

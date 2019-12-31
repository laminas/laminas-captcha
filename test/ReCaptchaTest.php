<?php

/**
 * @see       https://github.com/laminas/laminas-captcha for the canonical source repository
 * @copyright https://github.com/laminas/laminas-captcha/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-captcha/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Captcha;

use Laminas\Captcha\ReCaptcha;
use Laminas\ReCaptcha\ReCaptcha as ReCaptchaService;

/**
 * @group      Laminas_Captcha
 */
class ReCaptchaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        if (!getenv('TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT to test PDF render');
        }

        if (isset($this->word)) {
            unset($this->word);
        }

        $this->captcha = new ReCaptcha([
            'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer'
        ]);
    }

    public function testConstructorShouldSetOptions()
    {
        $options = [
            'privKey' => 'privateKey',
            'pubKey'  => 'publicKey',
            'ssl'     => true,
            'xhtml'   => true,
            'lang'    => null,
            'theme'   => 'red',
        ];
        $captcha = new ReCaptcha($options);
        $test    = $captcha->getOptions();
        $compare = ['privKey' => $options['privKey'], 'pubKey' => $options['pubKey']];
        $this->assertEquals($compare, $test);

        $service = $captcha->getService();
        $test = $service->getParams();
        $compare = ['ssl' => $options['ssl'], 'xhtml' => $options['xhtml']];
        foreach ($compare as $key => $value) {
            $this->assertArrayHasKey($key, $test);
            $this->assertSame($value, $test[$key]);
        }

        $test = $service->getOptions();
        $compare = ['lang' => $options['lang'], 'theme' => $options['theme']];
        $this->assertEquals($compare, $test);
    }

    public function testShouldAllowSpecifyingServiceObject()
    {
        $captcha = new ReCaptcha();
        $try     = new ReCaptchaService();
        $this->assertNotSame($captcha->getService(), $try);
        $captcha->setService($try);
        $this->assertSame($captcha->getService(), $try);
    }

    public function testSetAndGetPublicAndPrivateKeys()
    {
        $captcha = new ReCaptcha();
        $pubKey = 'pubKey';
        $privKey = 'privKey';
        $captcha->setPubkey($pubKey)
                ->setPrivkey($privKey);

        $this->assertSame($pubKey, $captcha->getPubkey());
        $this->assertSame($privKey, $captcha->getPrivkey());

        $this->assertSame($pubKey, $captcha->getService()->getPublicKey());
        $this->assertSame($privKey, $captcha->getService()->getPrivateKey());
    }

    public function testSetAndGetRecaptchaServicePublicAndPrivateKeysFromOptions()
    {
        $publicKey = 'publicKey';
        $privateKey = 'privateKey';
        $options = [
            'public_key' => $publicKey,
            'private_key' => $privateKey
        ];
        $captcha = new ReCaptcha($options);
        $this->assertSame($publicKey, $captcha->getService()->getPublicKey());
        $this->assertSame($privateKey, $captcha->getService()->getPrivateKey());
    }

    /** @group Laminas-7654 */
    public function testConstructorShouldAllowSettingLangOptionOnServiceObject()
    {
        $options = ['lang'=>'fr'];
        $captcha = new ReCaptcha($options);
        $this->assertEquals('fr', $captcha->getService()->getOption('lang'));
    }

    /** @group Laminas-7654 */
    public function testConstructorShouldAllowSettingThemeOptionOnServiceObject()
    {
        $options = ['theme'=>'black'];
        $captcha = new ReCaptcha($options);
        $this->assertEquals('black', $captcha->getService()->getOption('theme'));
    }

    /** @group Laminas-7654 */
    public function testAllowsSettingLangOptionOnServiceObject()
    {
        $captcha = new ReCaptcha;
        $captcha->setOption('lang', 'fr');
        $this->assertEquals('fr', $captcha->getService()->getOption('lang'));
    }

    /** @group Laminas-7654 */
    public function testAllowsSettingThemeOptionOnServiceObject()
    {
        $captcha = new ReCaptcha;
        $captcha->setOption('theme', 'black');
        $this->assertEquals('black', $captcha->getService()->getOption('theme'));
    }

    public function testUsesReCaptchaHelper()
    {
        $captcha = new ReCaptcha;
        $this->assertEquals('captcha/recaptcha', $captcha->getHelperName());
    }
}

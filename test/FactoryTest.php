<?php

/**
 * @see       https://github.com/laminas/laminas-captcha for the canonical source repository
 * @copyright https://github.com/laminas/laminas-captcha/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-captcha/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Captcha;

use DirectoryIterator;
use Laminas\Captcha;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    protected $testDir;
    protected $tmpDir;

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        // remove captcha images
        if (null !== $this->testDir) {
            foreach (new DirectoryIterator($this->testDir) as $file) {
                if (! $file->isDot() && ! $file->isDir()) {
                    unlink($file->getPathname());
                }
            }
        }
    }

    /**
     * Determine system TMP directory
     *
     * @return string
     * @throws Laminas_File_Transfer_Exception if unable to determine directory
     */
    protected function getTmpDir()
    {
        if (null === $this->tmpDir) {
            $this->tmpDir = sys_get_temp_dir();
        }
        return $this->tmpDir;
    }

    public function setUpImageTest()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('The GD extension is not available.');
            return;
        }
        if (! function_exists("imagepng")) {
            $this->markTestSkipped("Image CAPTCHA requires PNG support");
        }
        if (! function_exists("imageftbbox")) {
            $this->markTestSkipped("Image CAPTCHA requires FT fonts support");
        }

        $this->testDir = $this->getTmpDir() . '/Laminas_test_images';
        if (! is_dir($this->testDir)) {
            @mkdir($this->testDir);
        }
    }

    public function testCanCreateDumbCaptcha()
    {
        $captcha = Captcha\Factory::factory([
            'class' => 'Laminas\Captcha\Dumb',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\Dumb', $captcha);
    }

    public function testCanCreateDumbCaptchaUsingShortName()
    {
        $captcha = Captcha\Factory::factory([
            'class' => 'dumb',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\Dumb', $captcha);
    }

    public function testCanCreateFigletCaptcha()
    {
        $captcha = Captcha\Factory::factory([
            'class' => 'Laminas\Captcha\Figlet',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\Figlet', $captcha);
    }

    public function testCanCreateFigletCaptchaUsingShortName()
    {
        $captcha = Captcha\Factory::factory([
            'class' => 'figlet',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\Figlet', $captcha);
    }

    public function testCanCreateImageCaptcha()
    {
        $this->setUpImageTest();
        $captcha = Captcha\Factory::factory([
            'class' => 'Laminas\Captcha\Image',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
                'imgDir'       => $this->testDir,
                'font'         => __DIR__. '/../Pdf/_fonts/Vera.ttf',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\Image', $captcha);
    }

    public function testCanCreateImageCaptchaUsingShortName()
    {
        $this->setUpImageTest();
        $captcha = Captcha\Factory::factory([
            'class' => 'image',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
                'imgDir'       => $this->testDir,
                'font'         => __DIR__. '/../Pdf/_fonts/Vera.ttf',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\Image', $captcha);
    }

    public function testCanCreateReCaptcha()
    {
        if (! getenv('TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT to test PDF render');
        }

        $captcha = Captcha\Factory::factory([
            'class' => 'Laminas\Captcha\ReCaptcha',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\ReCaptcha', $captcha);
    }

    public function testCanCreateReCaptchaUsingShortName()
    {
        if (! getenv('TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT to test PDF render');
        }

        $captcha = Captcha\Factory::factory([
            'class' => 'recaptcha',
            'options' => [
                'sessionClass' => 'LaminasTest\Captcha\TestAsset\SessionContainer',
            ],
        ]);
        $this->assertInstanceOf('Laminas\Captcha\ReCaptcha', $captcha);
    }

    public function testOptionsArePassedToCaptchaAdapter()
    {
        $captcha = Captcha\Factory::factory([
            'class'   => 'LaminasTest\Captcha\TestAsset\MockCaptcha',
            'options' => [
                'foo' => 'bar',
            ],
        ]);
        $this->assertEquals(['foo' => 'bar'], $captcha->options);
    }
}

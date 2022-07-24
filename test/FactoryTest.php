<?php

declare(strict_types=1);

namespace LaminasTest\Captcha;

use DirectoryIterator;
use Laminas\Captcha;
use Laminas\Captcha\Dumb;
use Laminas\Captcha\Figlet;
use Laminas\Captcha\Image;
use Laminas\Captcha\ReCaptcha;
use LaminasTest\Captcha\TestAsset\MockCaptcha;
use LaminasTest\Captcha\TestAsset\SessionContainer;
use PHPUnit\Framework\TestCase;

use function extension_loaded;
use function function_exists;
use function getenv;
use function is_dir;
use function mkdir;
use function sys_get_temp_dir;
use function unlink;

class FactoryTest extends TestCase
{
    /** @var string */
    protected $testDir;

    /** @var string */
    protected $tmpDir;

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown(): void
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
     */
    protected function getTmpDir()
    {
        if (null === $this->tmpDir) {
            $this->tmpDir = sys_get_temp_dir();
        }
        return $this->tmpDir;
    }

    public function setUpImageTest(): void
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('The GD extension is not available.');
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

    public function testCanCreateDumbCaptcha(): void
    {
        $captcha = Captcha\Factory::factory([
            'class'   => Dumb::class,
            'options' => [
                'sessionClass' => SessionContainer::class,
            ],
        ]);
        $this->assertInstanceOf(Dumb::class, $captcha);
    }

    public function testCanCreateDumbCaptchaUsingShortName(): void
    {
        $captcha = Captcha\Factory::factory([
            'class'   => 'dumb',
            'options' => [
                'sessionClass' => SessionContainer::class,
            ],
        ]);
        $this->assertInstanceOf(Dumb::class, $captcha);
    }

    public function testCanCreateFigletCaptcha(): void
    {
        $captcha = Captcha\Factory::factory([
            'class'   => Figlet::class,
            'options' => [
                'sessionClass' => SessionContainer::class,
            ],
        ]);
        $this->assertInstanceOf(Figlet::class, $captcha);
    }

    public function testCanCreateFigletCaptchaUsingShortName(): void
    {
        $captcha = Captcha\Factory::factory([
            'class'   => 'figlet',
            'options' => [
                'sessionClass' => SessionContainer::class,
            ],
        ]);
        $this->assertInstanceOf(Figlet::class, $captcha);
    }

    public function testCanCreateImageCaptcha(): void
    {
        $this->setUpImageTest();
        $captcha = Captcha\Factory::factory([
            'class'   => Image::class,
            'options' => [
                'sessionClass' => SessionContainer::class,
                'imgDir'       => $this->testDir,
                'font'         => __DIR__ . '/../Pdf/_fonts/Vera.ttf',
            ],
        ]);
        $this->assertInstanceOf(Image::class, $captcha);
    }

    public function testCanCreateImageCaptchaUsingShortName(): void
    {
        $this->setUpImageTest();
        $captcha = Captcha\Factory::factory([
            'class'   => 'image',
            'options' => [
                'sessionClass' => SessionContainer::class,
                'imgDir'       => $this->testDir,
                'font'         => __DIR__ . '/../Pdf/_fonts/Vera.ttf',
            ],
        ]);
        $this->assertInstanceOf(Image::class, $captcha);
    }

    public function testCanCreateReCaptcha(): void
    {
        if (! getenv('TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT to test PDF render');
        }

        $captcha = Captcha\Factory::factory([
            'class'   => ReCaptcha::class,
            'options' => [
                'sessionClass' => SessionContainer::class,
            ],
        ]);
        $this->assertInstanceOf(ReCaptcha::class, $captcha);
    }

    public function testCanCreateReCaptchaUsingShortName(): void
    {
        if (! getenv('TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_LAMINAS_CAPTCHA_RECAPTCHA_SUPPORT to test PDF render');
        }

        $captcha = Captcha\Factory::factory([
            'class'   => 'recaptcha',
            'options' => [
                'sessionClass' => SessionContainer::class,
            ],
        ]);
        $this->assertInstanceOf(ReCaptcha::class, $captcha);
    }

    public function testOptionsArePassedToCaptchaAdapter(): void
    {
        $captcha = Captcha\Factory::factory([
            'class'   => MockCaptcha::class,
            'options' => [
                'foo' => 'bar',
            ],
        ]);
        $this->assertEquals(['foo' => 'bar'], $captcha->options);
    }
}

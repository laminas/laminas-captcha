<?php

declare(strict_types=1);

namespace LaminasTest\Captcha;

use ArrayObject;
use Laminas\Captcha\Figlet as FigletCaptcha;
use Laminas\Session\Container as SessionContainer;

use function strlen;

/**
 * @group      Laminas_Captcha
 */
class FigletTest extends CommonWordTest
{
    /** @var string */
    protected $wordClass = FigletCaptcha::class;

    /** @var FigletCaptcha */
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

        $this->captcha = new FigletCaptcha([
            'sessionClass' => \LaminasTest\Captcha\TestAsset\SessionContainer::class,
        ]);
    }

    public function testTimeoutPopulatedByDefault(): void
    {
        $ttl = $this->captcha->getTimeout();
        $this->assertNotEmpty($ttl);
        $this->assertIsInt($ttl);
    }

    public function testCanSetTimeout(): void
    {
        $ttl = $this->captcha->getTimeout();
        $this->captcha->setTimeout(3600);
        $this->assertNotEquals($ttl, $this->captcha->getTimeout());
        $this->assertEquals(3600, $this->captcha->getTimeout());
    }

    public function testGenerateReturnsId(): void
    {
        $id = $this->captcha->generate();
        $this->assertNotEmpty($id);
        $this->assertIsString($id);
        $this->id = $id;
    }

    public function testGetWordReturnsWord(): void
    {
        $this->captcha->generate();
        $word = $this->captcha->getWord();
        $this->assertNotEmpty($word);
        $this->assertIsString($word);
        $this->assertEquals(8, strlen($word));
        $this->word = $word;
    }

    public function testGetWordLength(): void
    {
        $this->captcha->setWordLen(4);
        $this->captcha->generate();
        $word = $this->captcha->getWord();
        $this->assertIsString($word);
        $this->assertEquals(4, strlen($word));
        $this->word = $word;
    }

    public function testGenerateIsRandomised(): void
    {
        $id1   = $this->captcha->generate();
        $word1 = $this->captcha->getWord();
        $id2   = $this->captcha->generate();
        $word2 = $this->captcha->getWord();

        $this->assertNotEmpty($id1);
        $this->assertNotEmpty($id2);
        $this->assertNotEquals($id1, $id2);
        $this->assertNotEquals($word1, $word2);
    }

    public function testGenerateInitializesSessionData(): void
    {
        $this->captcha->generate();
        $session = $this->captcha->getSession();
        $this->assertEquals($this->captcha->getTimeout(), $session->setExpirationSeconds);
        $this->assertEquals(1, $session->setExpirationHops);
        $this->assertEquals($this->captcha->getWord(), $session->word);
    }

    public function testWordValidates(): void
    {
        $this->captcha->generate();
        $input = ['id' => $this->captcha->getId(), 'input' => $this->captcha->getWord()];
        $this->assertTrue($this->captcha->isValid($input));
    }

    public function testMissingNotValid(): void
    {
        $this->captcha->generate();
        $this->assertFalse($this->captcha->isValid(''));
        $this->assertFalse($this->captcha->isValid([]));
        $input = ['input' => 'blah'];
        $this->assertFalse($this->captcha->isValid($input));
    }

    public function testWrongWordNotValid(): void
    {
        $this->captcha->generate();
        $input = ["id" => $this->captcha->getId(), "input" => "blah"];
        $this->assertFalse($this->captcha->isValid($input));
    }

    public function testUsesFigletCaptchaHelperByDefault(): void
    {
        $this->assertEquals('captcha/figlet', $this->captcha->getHelperName());
    }

    public function testCaptchaShouldBeConfigurableViaTraversableObject(): void
    {
        $options = [
            'name'         => 'foo',
            'sessionClass' => \LaminasTest\Captcha\TestAsset\SessionContainer::class,
            'wordLen'      => 6,
            'timeout'      => 300,
        ];
        $config  = new ArrayObject($options);
        $captcha = new FigletCaptcha($config);
        $test    = $captcha->getOptions();
        $this->assertEquals($options, $test);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldAllowFigletsLargerThanFourteenCharacters(): void
    {
        $this->captcha->setName('foo')
                      ->setWordLen(14);
        $this->captcha->generate();
    }

    public function testShouldNotValidateEmptyInputAgainstEmptySession(): void
    {
        // Regression Test for Laminas-4245
        $this->captcha->setName('foo')
                      ->setWordLen(6)
                      ->setTimeout(300);
        $id = $this->captcha->generate();

        // Unset the generated word
        // we have to reset $this->captcha for that
        $this->captcha->getSession()->word = null;
        $this->setUp();
        $this->captcha->setName('foo')
                      ->setWordLen(6)
                      ->setTimeout(300);
        $empty = [$this->captcha->getName() => ['id' => $id, 'input' => '']];
        $this->assertEquals(false, $this->captcha->isValid(null, $empty));
    }

    /**
     * @group Laminas-5728
     */
    public function testSetSessionWorks(): void
    {
        $session = new SessionContainer('captcha');
        $this->captcha->setSession($session);
        $this->captcha->generate();
        $input = ["id" => $this->captcha->getId(), "input" => $this->captcha->getWord()];
        $this->assertTrue($this->captcha->isValid($input));
        $this->assertEquals($session->word, $this->captcha->getWord());
    }
}

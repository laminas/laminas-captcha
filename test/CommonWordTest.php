<?php

/**
 * @see       https://github.com/laminas/laminas-captcha for the canonical source repository
 * @copyright https://github.com/laminas/laminas-captcha/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-captcha/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Captcha;

use Laminas\Captcha\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group      Laminas_Captcha
 */
abstract class CommonWordTest extends TestCase
{
    /**
     * Word adapter class name
     *
     * @var string
     */
    protected $wordClass;

    /**
     * @group Laminas-91
     */
    public function testLoadInvalidSessionClass()
    {
        $wordAdapter = new $this->wordClass;
        $wordAdapter->setSessionClass('LaminasTest\Captcha\InvalidClassName');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('not found');
        $wordAdapter->getSession();
    }

    public function testErrorMessages()
    {
        $wordAdapter = new $this->wordClass;
        $this->assertFalse($wordAdapter->isValid('foo'));
        $messages = $wordAdapter->getMessages();
        $this->assertNotEmpty($messages);
    }
}

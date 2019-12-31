<?php

/**
 * @see       https://github.com/laminas/laminas-captcha for the canonical source repository
 * @copyright https://github.com/laminas/laminas-captcha/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-captcha/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Captcha;

use Laminas\Stdlib\ArrayUtils;
use Traversable;

abstract class Factory
{
    /**
     * @var array Known captcha types
     */
    protected static $classMap = [
        'dumb'      => 'Laminas\Captcha\Dumb',
        'figlet'    => 'Laminas\Captcha\Figlet',
        'image'     => 'Laminas\Captcha\Image',
        'recaptcha' => 'Laminas\Captcha\ReCaptcha',
    ];

    /**
     * Create a captcha adapter instance
     *
     * @param  array|Traversable $options
     * @return AdapterInterface
     * @throws Exception\InvalidArgumentException for a non-array, non-Traversable $options
     * @throws Exception\DomainException if class is missing or invalid
     */
    public static function factory($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (! is_array($options)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($options) ? get_class($options) : gettype($options))
            ));
        }

        if (! isset($options['class'])) {
            throw new Exception\DomainException(sprintf(
                '%s expects a "class" attribute in the options; none provided',
                __METHOD__
            ));
        }

        $class = $options['class'];
        if (isset(static::$classMap[strtolower($class)])) {
            $class = static::$classMap[strtolower($class)];
        }
        if (! class_exists($class)) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "class" attribute to resolve to an existing class; received "%s"',
                __METHOD__,
                $class
            ));
        }

        unset($options['class']);

        if (isset($options['options'])) {
            $options = $options['options'];
        }
        $captcha = new $class($options);

        if (! $captcha instanceof AdapterInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "class" attribute to resolve to a valid %s instance; received "%s"',
                __METHOD__,
                AdapterInterface::class,
                $class
            ));
        }

        return $captcha;
    }
}

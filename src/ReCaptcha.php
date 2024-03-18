<?php

declare(strict_types=1);

namespace Laminas\Captcha;

use Laminas\ReCaptcha\RecaptchaServiceInterface;

use function array_key_exists;
use function is_array;
use function is_int;
use function is_string;

/**
 * ReCaptcha adapter
 *
 * Allows inserting captchas driven by ReCaptcha service
 *
 * @see http://recaptcha.net/apidocs/captcha/
 */
class ReCaptcha extends AbstractAdapter
{
    /**
     * Recaptcha service object
     *
     * @var RecaptchaServiceInterface
     */
    protected $service;

    /**
     * Parameters defined by the service
     *
     * @var array
     */
    protected $serviceParams = [];

    /**
     * Options defined by the service
     *
     * @var array
     */
    protected $serviceOptions = [];

    /**#@+
     * Error codes
     */
    public const MISSING_VALUE = 'missingValue';
    public const ERR_CAPTCHA   = 'errCaptcha';
    public const BAD_CAPTCHA   = 'badCaptcha';
    /**#@-*/

    /**
     * Error messages
     *
     * @var array
     */
    protected $messageTemplates = [
        self::MISSING_VALUE => 'Missing captcha fields',
        self::ERR_CAPTCHA   => 'Failed to validate captcha',
        self::BAD_CAPTCHA   => 'Captcha value is wrong',
    ];

    /**
     * Retrieve ReCaptcha Secret key
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->getService()->getSecretKey();
    }

    /**
     * Retrieve ReCaptcha Site key
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->getService()->getSiteKey();
    }

    /**
     * Set ReCaptcha private key
     *
     * @param  string $secretKey
     * @return ReCaptcha Provides a fluent interface
     */
    public function setSecretKey($secretKey)
    {
        $this->getService()->setSecretKey($secretKey);
        return $this;
    }

    /**
     * Set ReCaptcha site key
     *
     * @param  string $siteKey
     * @return ReCaptcha Provides a fluent interface
     */
    public function setSiteKey($siteKey)
    {
        $this->getService()->setSiteKey($siteKey);
        return $this;
    }

    /**
     * Retrieve ReCaptcha secret key (BC version)
     *
     * @deprecated
     *
     * @return string
     */
    public function getPrivKey()
    {
        return $this->getSecretKey();
    }

    /**
     * Retrieve ReCaptcha site key (BC version)
     *
     * @deprecated
     *
     * @return string
     */
    public function getPubKey()
    {
        return $this->getSiteKey();
    }

    /**
     * Set ReCaptcha secret key (BC version)
     *
     * @deprecated
     *
     * @param  string $key
     * @return ReCaptcha Provides a fluent interface
     */
    public function setPrivKey($key)
    {
        return $this->setSecretKey($key);
    }

    /**
     * Set ReCaptcha site key (BC version)
     *
     * @deprecated
     *
     * @param  string $key
     * @return ReCaptcha Provides a fluent interface
     */
    public function setPubKey($key)
    {
        return $this->setSiteKey($key);
    }

    /**
     * Constructor
     *
     * @param array<string, mixed>|null $options
     */
    public function __construct($options = null)
    {
        $this->setService($options['service'] ?? new \Laminas\ReCaptcha\ReCaptcha());
        $this->serviceParams  = $this->getService()->getParams();
        $this->serviceOptions = $this->getService()->getOptions();

        parent::__construct($options);

        if (! empty($options)) {
            if (array_key_exists('secret_key', $options) && is_string($options['secret_key'])) {
                $this->getService()->setSecretKey($options['secret_key']);
            }
            if (array_key_exists('site_key', $options)) {
                $this->getService()->setSiteKey($options['site_key']);
            }

            // Support pubKey and pubKey for BC
            if (array_key_exists('privKey', $options) && is_string($options['privKey'])) {
                $this->getService()->setSecretKey($options['privKey']);
            }
            if (array_key_exists('pubKey', $options)) {
                $this->getService()->setSiteKey($options['pubKey']);
            }

            $this->setOptions($options);
        }
    }

    /**
     * Set service object
     *
     * @return ReCaptcha Provides a fluent interface
     */
    public function setService(RecaptchaServiceInterface $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Retrieve ReCaptcha service object
     *
     * @return RecaptchaServiceInterface
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set option
     *
     * If option is a service parameter, proxies to the service. The same
     * goes for any service options (distinct from service params)
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this Provides a fluent interface
     */
    public function setOption($key, $value)
    {
        $service = $this->getService();
        if (array_key_exists($key, $this->serviceParams)) {
            $service->setParam($key, $value);
            return $this;
        }
        if (array_key_exists($key, $this->serviceOptions)) {
            $service->setOption($key, $value);
            return $this;
        }
        return parent::setOption($key, $value);
    }

    /**
     * Generate captcha
     *
     * @see AbstractAdapter::generate()
     *
     * @return string
     */
    public function generate()
    {
        return "";
    }

    /**
     * Validate captcha.
     *
     * The value should contain the name of the key within the context that
     * contains the ReCaptcha data. The default within the ReCaptcha service
     * for this is "g-recaptcha-response"
     *
     * @see    \Laminas\Validator\ValidatorInterface::isValid()
     *
     * @param  mixed $value
     * @param  mixed $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (empty($value) && ! is_array($context)) {
            $this->error(self::MISSING_VALUE);
            return false;
        }

        $service = $this->getService();
        if ((is_string($value) || is_int($value)) && array_key_exists($value, $context)) {
            $res = $service->verify($context[$value]);
        } else {
            $res = $service->verify($value);
        }

        if (! $res->isValid()) {
            $this->error(self::BAD_CAPTCHA);
            return false;
        }

        return true;
    }

    /**
     * Get helper name used to render captcha
     *
     * @return string
     */
    public function getHelperName()
    {
        return "captcha/recaptcha";
    }
}

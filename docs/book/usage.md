# Usage

A basic use case resembles the following:

```php
// Originating request:
$captcha = new Laminas\Captcha\Figlet([
    'name'    => 'foo',
    'wordLen' => 6,
    'timeout' => 300,
]);

$id = $captcha->generate();

// This will output a Figlet string:
echo $captcha->getFiglet()->render($captcha->getWord());

// On a subsequent request:
// Assume a captcha setup as before, with corresponding form fields, the value
// of $_POST['foo'] would be a key/value array containing:
// - id => captcha ID
// - input => captcha value
if ($captcha->isValid($_POST['foo'], $_POST)) {
    // Validated!
}
```

The above example demonstrates usage of a FIGlet string for the CAPTCHA.
laminas-captcha also provides adapters for:

- Images
- [reCAPTCHA](https://www.google.com/recaptcha/intro/index.html)

and an interface allowing you to define and implement your own solutions.

The options required will vary based on the adapter you use, but in all cases,
you will use the combination of:

- `generate()`
- some mechanism of the adapter to render the CAPTCHA
- `isValid()` to validate a submitted CAPTCHA solution

> NOTE: laminas-form integration
>
> [laminas-form](https://github.com/laminas/laminas-form) contains integration
> with laminas-captcha via the class `Laminas\Form\Element\Captcha`; read the
> [documentation on the CAPTCHA form element](https://docs.laminas.dev/laminas-form/element/captcha/)
> for more details.

# My OctoberCMS Utility

## ReCaptcha

### Add Google ReCaptcha key in your .env file

```php
RECAPTCHA_SECRET_KEY='your_google_recaptcha_secret_key'
RECAPTCHA_SITE_KEY='your_google_recaptcha_site_key'
```

### Add ReCaptcha Components

Add ReCaptcha Component inside your form.

```
 <form
        role="form"
        data-request="{{ __SELF__ }}::onSubmitAnswer" 
        data-request-update="'{{ __SELF__ }}::result': '#formContainer'"
        data-request-flash>
        {% component 'recaptcha' %}
 </form>
```

### Add server Validation part into your Backend

```php
    // spam?
    $isReCaptchaValid = Validator::make(post(), [
        'g-recaptcha-response' => ['required', new \Yfktn\YfktnUtil\Classes\ReCaptchaValidator],
    ]);
    if($isReCaptchaValid->fails()) {
        throw new ApplicationException("ReCaptcha Failed!");
    }
```

You can add this validation either in your ajax method component or `beforeValidate` function of your model.
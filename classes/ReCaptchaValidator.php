<?php namespace Yfktn\YfktnUtil\Classes;

class ReCaptchaValidator implements \Illuminate\Contracts\Validation\Rule
{

    public function passes($attribute, $value)
    {
        $recaptchaResponse = post('g-recaptcha-response');
        $recaptchaSecretKey = config('yfktn.yfktnutil::recaptcha.secretKey');
        $ip = request()->getClientIp();
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptchaSecretKey . '&response=' . $recaptchaResponse . '&remoteip=' . $ip;
        $response = json_decode(file_get_contents($url), true);
        return ($response['success'] == true && $response['score'] >= 0.5);
    }

    public function message()
    {
        return 'The reCAPTCHA field is not valid.';
    }
}
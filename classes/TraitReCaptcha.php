<?php namespace Yfktn\YfktnUtil\Classes;

trait TraitReCaptcha
{

    protected function loadAssetsReCaptcha()
    {
        $this->addJs(
            'https://www.google.com/recaptcha/api.js?render=' . $this->getRecaptchaSiteKey(), [
            'defer' => true
        ]);
        $this->addJs(
            '/plugins/yfktn/yfktnutil/assets/recaptcha.js', [
                'defer' => true
            ]
        );
    }

    protected function getRecaptchaSiteKey()
    {
        return config('yfktn.yfktnutil::recaptcha.siteKey');
    }

    
}
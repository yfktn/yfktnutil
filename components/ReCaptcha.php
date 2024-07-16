<?php namespace Yfktn\YfktnUtil\Components;

use Cms\Classes\ComponentBase;
use Yfktn\YfktnUtil\Classes\TraitReCaptcha;

/**
 * ReCaptcha Component
 */
class ReCaptcha extends ComponentBase
{
    use TraitReCaptcha;
    public function componentDetails()
    {
        return [
            'name' => 'ReCaptcha 3 Component',
            'description' => 'Menggunakan ReCaptcha 3'
        ];
    }

    public function onRun()
    {
        $this->loadAssetsReCaptcha();
        $this->prepareTheVars();
    }

    protected function prepareTheVars()
    {
        $this->page['recaptchaSiteKey'] = config('yfktn.yfktnutil::recaptcha.siteKey');
    }

    public function defineProperties()
    {
        return [];
    }
}

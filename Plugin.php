<?php namespace Yfktn\YfktnUtil;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Yfktn\YfktnUtil\Components\ReCaptcha' => 'recaptcha'
        ];
    }

    public function registerSettings()
    {
    }
}

<?php namespace Yfktn\YfktnUtil\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * ReCaptcha Form Widget
 */
class ReCaptcha extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'yfktn_re_captcha';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('recaptcha');
    }

    /**
     * prepareVars for view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        // $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/recaptcha.css', 'Yfktn.YfktnUtil');
        $this->addJs('https://www.google.com/recaptcha/api.js', [
            'defer' => true
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return \Backend\Classes\FormField::NO_SAVE_DATA;
    }
}

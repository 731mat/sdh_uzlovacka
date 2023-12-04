<?php

namespace App\Module\Admin\Presenters;

use App\Components\SystemVariable\SystemVariableControl;
use App\Components\SystemVariable\SystemVariableFactory;
use Nette;
use App\Model;
use Nette\Security\User;
use Nette\Security\Authenticator;
use Nette\Forms\Controls;
use AdminModule\Components\Theme\Theme;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @var Nette\Security\User @inject */
    public $userData;

    /** @var Theme @inject */
    private Theme $theme;



    /** @var SystemVariableFactory @inject */
    public $systemVariableFactory;

    protected function createComponentSystemVariable(): SystemVariableControl
    {
        return $this->systemVariableFactory->create();
    }

    public function startup()
    {
        parent::startup();

        if($this->name != 'Admin:Sign' && !$this->getUser()->isLoggedIn()){
            $this->redirect(':Admin:Sign:in');
        }

        $this->template->addFilter('cas', function ($time) {
            if ($time == null){
                return "";
            }
            $minutes = floor($time / 60);
            $seconds = $time - ($minutes * 60);
            $fraction = round(($seconds - floor($seconds)) * 100);

            return sprintf("%02d:%02d.%02d", $minutes, floor($seconds), $fraction);
        });

    }


    public function beforeRender()
    {
        $this->template->user = $this->getUser();
    }
    public function renderForm(&$form) {
        // setup form rendering

        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;
        $renderer->wrappers['pair']['container'] = 'div class=form-group';
        $renderer->wrappers['pair']['.error'] = 'has-error';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-12';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-6 control-label"';
        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
        // make form and controls compatible with Twitter Bootstrap


        if(array_key_exists("class", $form->getElementPrototype()->class()->attrs))
            $form->getElementPrototype()->class('form-horizontal '.$form->getElementPrototype()->class()->attrs['class']);
        else
            $form->getElementPrototype()->class('form-horizontal');


        foreach ($form->getControls() as $control) {
            if ($control instanceof Controls\Button) {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
                $usedPrimary = TRUE;
            } elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
                $control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
            } elseif ($control instanceof Controls\TextInput ) {
                dump($control);
            }
        }
    }


    public function renderFormLogin(&$form) {
        // setup form rendering
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;
        $renderer->wrappers['pair']['container'] = 'div class=form-group';
        $renderer->wrappers['pair']['.error'] = 'has-error';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';
        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
        // make form and controls compatible with Twitter Bootstrap
        $form->getElementPrototype()->class('form-horizontal');
        foreach ($form->getControls() as $control) {
            if ($control instanceof Controls\Button) {
                $control->getControlPrototype()->addClass('btn btn-default submit');
            } elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
                $control->getControlPrototype()->addClass('form-control');
            }
        }
    }

    public function makeBootstrap4(&$form): void
    {
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = null;
        $renderer->wrappers['pair']['container'] = 'div class="form-group row"';
        $renderer->wrappers['pair']['.error'] = 'has-danger';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-10';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-2 col-form-label"';
        $renderer->wrappers['control']['description'] = 'span class=form-text';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=form-control-feedback';
        $renderer->wrappers['control']['.error'] = 'is-invalid';

        foreach ($form->getControls() as $control) {
            $type = $control->getOption('type');
            if ($type === 'button') {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary');
                $usedPrimary = true;

            } elseif (in_array($type, ['text', 'textarea', 'select'], true)) {
                $control->getControlPrototype()->addClass('form-control');

            } elseif ($type === 'file') {
                $control->getControlPrototype()->addClass('form-control-file');

            } elseif (in_array($type, ['checkbox', 'radio'], true)) {
                if ($control instanceof Nette\Forms\Controls\Checkbox) {
                    $control->getLabelPrototype()->addClass('form-check-label');
                } else {
                    $control->getItemLabelPrototype()->addClass('form-check-label');
                }
                $control->getControlPrototype()->addClass('form-check-input');
                $control->getSeparatorPrototype()->setName('div')->addClass('form-check');
            }
        }
    }
    public function makeBootstrap5(&$form): void
    {
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = null;
        $renderer->wrappers['pair']['container'] = 'div class="row mb-4"';
        $renderer->wrappers['pair']['.error'] = 'has-danger';
        $renderer->wrappers['control']['container'] = 'div class=col-md-10';
        $renderer->wrappers['label']['container'] = 'div class="col-md-2 form-label"';
        $renderer->wrappers['control']['description'] = 'span class=form-text';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=form-control-feedback';
        $renderer->wrappers['control']['.error'] = 'is-invalid';
        //$form->getElementPrototype()->class('form-horizontal');

        foreach ($form->getControls() as $control) {
            $type = $control->getOption('type');
            if ($type === 'button') {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary');
                $usedPrimary = true;

            } elseif (in_array($type, ['text', 'textarea', 'select'], true)) {
                $control->getControlPrototype()->addClass('form-control');

            } elseif ($type === 'file') {
                $control->getControlPrototype()->addClass('btn form-control-file');

            } elseif (in_array($type, ['checkbox', 'radio'], true)) {
                if ($control instanceof Nette\Forms\Controls\Checkbox) {
                    $control->getLabelPrototype()->addClass('form-check-label');
                } else {
                    $control->getItemLabelPrototype()->addClass('form-check-label');
                }
                $control->getControlPrototype()->addClass('form-check-input');
                $control->getSeparatorPrototype()->setName('div')->addClass('form-check');
            }
        }
    }


    /**
     * @internal
     * @param Theme $theme
     */
    public function injectTheme(Theme $theme): void
    {
        $this->theme = $theme;
        $this->template->isDarkMode = $this->theme->isDarkMode();
    }


    public function handleChangeFuture(): void
    {
        if ($this->theme->isDarkMode())
            $this->theme->setLightMode();
        else
            $this->theme->setDarkMode();
    }
}

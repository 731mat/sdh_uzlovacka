<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\SystemVariableManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


final class SystemVariablePresenter extends BasePresenter
{

    private SystemVariableManager $systemVariableManager;

    public function __construct(SystemVariableManager $systemVariableManager)
    {
        parent::__construct();
        $this->systemVariableManager = $systemVariableManager;
    }

    public function handleEdit($id){
        $defaultValue = $this->systemVariableManager->getVariable($id);

        $this['settingsForm']->setDefaults($defaultValue);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->redrawControl('tabulkaContainer');
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function handleAdd(){
        $this['settingsForm']->setDefaults([]);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function renderDefault()
    {
        $this->template->list = $this->systemVariableManager->getVariables();
    }

    protected function createComponentSettingsForm()
    {
        $form = new Form;
        $form->getElementPrototype()->class('ajax');
        $form->addHidden('id');
        $form->addText('nazev', 'Název')->setRequired();
        $form->addText('val', 'Hodnota')->setRequired();

        $form->addSubmit('save', 'Uložit');
        $this->makeBootstrap5($form);
        $form->onSuccess[] = function (Form $form, ArrayHash $values) {

            try {
                if ($values->id == ''){
                    $values->id = $this->make_alias($values->nazev);
                    $this->systemVariableManager->addVariable($values);
                    $this->flashMessage('Sloupec byl přidán');
                }else{
                    $this->systemVariableManager->editVariable($values->id, $values);
                    $this->flashMessage('Sloupec byl upraven');
                }

                $this->template->list = $this->systemVariableManager->getVariables();
                if (!$this->isAjax())
                    $this->redirect(':default');
                else {
                    $this->payload->isModal = false;
                    $this->redrawControl("modal");
                    $this->redrawControl('tabulkaContainer');
                }

            } catch (UniqueConstraintViolationException $e) {
                $this->flashMessage('Článek s touto URL adresou již existuje.');
            }
        };

        return $form;
    }

    protected function make_alias($variable)
    {
        $text = htmlspecialchars(trim($variable));
        $text = str_replace( '#', '_', $text );
        $text = str_replace( '&', '_', $text );
        $text = str_replace( ';', '_', $text );
        $text = str_replace( ' ', '_', $text );
        $text = str_replace( '!', '_', $text );
        $text = str_replace( '"', '_', $text );
        $text = str_replace( '$', '_', $text );
        $text = str_replace( '%', '_', $text );
        $text = str_replace( "'", '_', $text );
        $text = str_replace( '(', '_', $text );
        $text = str_replace( ')', '_', $text );
        $text = str_replace( '*', '_', $text );
        $text = str_replace( '+', '_', $text );
        $text = str_replace( ',', '_', $text );
        $text = str_replace( '-', '_', $text );
        $text = str_replace( '.', '_', $text );
        $text = str_replace( ':', '_', $text );
        $text = str_replace( '<', '_', $text );
        $text = str_replace( '=', '_', $text );
        $text = str_replace( '>', '_', $text );
        $text = str_replace( '?', '_', $text );
        $text = str_replace( '[', '_', $text );
        $text = str_replace( '\\', '_', $text );
        $text = str_replace( ']', '_', $text );
        $text = str_replace( '^', '_', $text );
        $text = str_replace( '_', '_', $text );
        $text = str_replace( '`', '_', $text );
        $text = str_replace( '{', '_', $text );
        $text = str_replace( '|', '_', $text );
        $text = str_replace( '}', '_', $text );
        $text = str_replace( '~', '_', $text );


        $chars = array("\r\n", '\\n', '\\r', "\n", "\r", "\t", "\0", "\x0B");
        $text = str_replace($chars,"",$text);

        $diak = array("á","č","ď","é","ě","í","ň","ó","ř","š","ť","ú","ů","ý","ž","Á","Č","Ď","É","Ě","Í","Ň","Ó","Ř","Š","Ť","Ú","Ů","Ý","Ž");
        $nahr = array("a","c","d","e","e","i","n","o","r","s","t","u","u","y","z","A","C","D","E","E","I","N","O","R","S","T","U","U","Y","Z");
        $text = str_ireplace($diak, $nahr, $text);

        $text = strtolower($text);
        return $text;
    }
}

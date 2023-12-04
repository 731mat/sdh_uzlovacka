<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\Race\RaceCategoryManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


final class RaceCategoryPresenter extends BasePresenter
{

    private RaceCategoryManager $raceCategoryManager;

    public function __construct(RaceCategoryManager $raceCategoryManager)
    {
        parent::__construct();
        $this->raceCategoryManager = $raceCategoryManager;
    }

    public function handleEdit($id){
        $defaultValue = $this->raceCategoryManager->getCategory($id);

        $this['raceCategoryForm']->setDefaults($defaultValue);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->redrawControl('tabulkaContainer');
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function handleAdd($raceid){
        $this['raceCategoryForm']->setDefaults(['race_id'=>$raceid]);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function renderDefault($raceid)
    {
        if ($raceid == null) $this->redirect('RaceList:default');
        $this->template->categorys = $this->raceCategoryManager->getCategoryByRace($raceid);
    }

    protected function createComponentRaceCategoryForm()
    {
        $form = new Form;
        $form->getElementPrototype()->class('ajax');
        $form->addHidden('id')->setNullable();
        $form->addHidden('race_id');
        $form->addText('name','name')->setRequired();
        $form->addTextArea('description','description')->setRequired();

        $form->addSubmit('save', 'Uložit');
        $this->makeBootstrap5($form);
        $form->onSuccess[] = function (Form $form, ArrayHash $values) {

            try {
                if ($values->id == ''){
                    $this->raceCategoryManager->addCategory($values);
                    $this->flashMessage('Sloupec byl přidán');
                }else{
                    $this->raceCategoryManager->editCategory($values->id, $values);
                    $this->flashMessage('Sloupec byl upraven');
                }

                $this->template->categorys = $this->raceCategoryManager->getCategorys();
                if (!$this->isAjax())
                    $this->redirect(':default', array('race_id'=>$values->race_id));
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
}

<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\CompetitorManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


final class CompetitorPresenter extends BasePresenter
{

    private CompetitorManager $competitorManager;

    public function __construct(CompetitorManager $competitorManager)
    {
        parent::__construct();
        $this->competitorManager = $competitorManager;
    }

    public function handleEdit($id){
        $defaultValue = $this->competitorManager->getUser($id);

        $this['raceUserForm']->setDefaults($defaultValue);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->redrawControl('tabulkaContainer');
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function handleAdd($raceid){
        $this['raceUserForm']->setDefaults([]);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }
    public function actionTest($id){
        $this->competitorManager->generateDiplom($id);
    }

    public function renderDefault()
    {
        $this->template->users = $this->competitorManager->getUsers();
    }

    protected function createComponentRaceUserForm()
    {
        $categorys = array(1=>"Mladši",2=>"Starší",3=>"Dorost");
        $form = new Form;
        $form->getElementPrototype()->class('ajax');
        $form->addHidden('id')->setDefaultValue(null);
        $form->addSelect('category','Kategorie',$categorys)->setRequired();
        $form->addInteger('start_number','Startovní čílo:')->setDefaultValue($this->competitorManager->getNewStartNumber()+1)->setRequired();
        $form->addText('first_name','Jméno:')->setRequired();
        $form->addText('last_name','Přijmení')->setRequired();
        $form->addInteger('year','Ročník:')->setRequired();
        $form->addText('sdh','SDH')->setRequired();
        $form->addInteger('first_time','Čas')->setRequired();
        $form->addInteger('score','skóré')->setDefaultValue(0)->setRequired();
        $form->addInteger('round','kolo')->setDefaultValue(0)->setRequired();
        $form->addInteger('vysledne_poradi','Výsledne pořadí')->setDefaultValue(0)->setRequired();

        $form->addSubmit('save', 'Uložit');
        $this->makeBootstrap5($form);
        $form->onSuccess[] = function (Form $form, ArrayHash $values) {

            try {
                if ($values->id == ''){
                    $values->id = null;
                    $values->register_hash = "";
                    $this->competitorManager->addUser($values);
                    $this->flashMessage('Sloupec byl přidán');
                }else{
                    $this->competitorManager->editUser($values->id, $values);
                    $this->flashMessage('Sloupec byl upraven');
                }

                $this->template->users = $this->competitorManager->getUsers();
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


    public function renderResults($id){
        $this->template->results = $this->competitorManager->getResults($id);
    }
}

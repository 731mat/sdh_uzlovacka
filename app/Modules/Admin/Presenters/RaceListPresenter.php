<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\Race\RaceListManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


final class RaceListPresenter extends BasePresenter
{

    private RaceListManager $raceListManager;
    const PATH_RACE_IMG = "/var/www/html/www/files/race/";

    public function __construct(RaceListManager $raceListManager)
    {
        parent::__construct();
        $this->raceListManager = $raceListManager;
    }

    public function handleEdit($id){
        $defaultValue = $this->raceListManager->getRace($id);

        $this['raceListForm']->setDefaults($defaultValue);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->redrawControl('tabulkaContainer');
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function handleAdd(){
        $this['raceListForm']->setDefaults([]);
        if (!$this->isAjax())
            $this->redirect(':default');
        else {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");
        }
    }

    public function renderDefault()
    {
        $this->template->races = $this->raceListManager->getRaces();
    }

    protected function createComponentRaceListForm()
    {
        $form = new Form;
        $form->getElementPrototype()->class('ajax');
        $form->addHidden('id');
        $form->addText('name','name')->setRequired();
        $form->addTextArea('description','description')->setRequired();
        $form->addText('date','date')->setRequired();
        $form->addText('place','place')->setRequired();
        $form->addUpload('photo', 'obrázek/logo:')
            ->addRule($form::IMAGE, 'Avatar must be JPEG, PNG, GIF or WebP')
            ->addRule($form::MAX_FILE_SIZE, 'Maximum size is 1 MB', 2* 1024 * 1024);
        $form->addTextArea('regulations','regulations')->setRequired();
        $form->addText('web','web')->setRequired();

        $form->addSubmit('save', 'Uložit');
        $this->makeBootstrap5($form);
        $form->onSuccess[] = function (Form $form, ArrayHash $values) {

            if($values['photo']->hasFile() && $values['photo']->isImage() && $values['photo']->name != null){
                $hash = bin2hex(random_bytes(8));
                $values['photo']->move(self::PATH_RACE_IMG.$hash.'.'.$values['photo']->getImageFileExtension());
                $values['photo'] = $hash.'.'.$values['photo']->getImageFileExtension();
            }else{
                unset($values['photo']);
            }

            try {
                if ($values->id == ''){
                    $this->raceListManager->addRace($values);
                    $this->flashMessage('Sloupec byl přidán');
                }else{
                    $this->raceListManager->editRace($values->id, $values);
                    $this->flashMessage('Sloupec byl upraven');
                }

                $this->template->list = $this->raceListManager->getRaces();
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

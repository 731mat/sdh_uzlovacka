<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use App\Model\CompetitorManager;
use App\Model\EmailManager\SendEmailManager;
use App\Model\Race\RaceListManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;


final class RegistracePresenter extends BasePresenter
{


    private CompetitorManager $competitorManager;
    public function __construct(CompetitorManager $competitorManager)
    {
        parent::__construct();

        $this->competitorManager = $competitorManager;
    }
    public function renderZaregistrovani(){
        $this->template->registred = $this->competitorManager->getUsers();
    }
    public function renderDefault($id){

        $session = $this->getSession();
        $section = $session->getSection('registation-');
        $countUsers = $section->get('countUsers');
        $form_data = $section->get('form_data');
        $usersToPay = $section->get('usersToPay');
        $hash = $section->get('hash');

        if ($hash != null && $hash != ""){
            $id = $hash;
        }
        if ($id != null) {
            $this->template->registred = $this->competitorManager->getUserByHash($id);
            $this->template->hash_old = $id;
        }

        if ($form_data != null){
            $this->template->form_data = $form_data;
        }
        if($countUsers != null){
            $this->template->countUsers = $countUsers;
        }
        if($usersToPay != null){
            $this->template->usersToPay = $usersToPay;
        }

        if (!isset($this->template->countUsers)){
            $this->template->countUsers = 0;
        }

        $this->template->categorys = array(1=>"Mladši",2=>"Starší",3=>"Dorost");
        $this->template->categorysAsc = array(1=>"Mladši",2=>"Starší",3=>"Dorost");
        // TODO: category $this->template->race = $this->raceListManager->getRace($id);

    }



    public function renderRegistratioCompleted($id){
        $userRegister = $this->raceUserManager->getUserByHash($id);
        if ($userRegister == null) $this->redirect('Homepage:default');
        $this->template->userRegister = $userRegister;
        $racer = $userRegister->fetch();
        $raceId = $racer['id_race'];
        $this->template->race = $this->raceListManager->getRace($raceId);

        SendEmailManager::sendEmail($racer['email'],"Registrace do závodu",$this->raceListManager->getRace($raceId)['name'],"děkujeme ti za tvou registraci bla bla",$racer['hash']);

    }
    public function renderResults($id){
        $this->template->race = $this->raceListManager->getRace($id);
        // TODO: category $this->template->race = $this->raceListManager->getRace($id);
    }




    public function doplnitForm(SubmitButton $button)
    {
        $data = $button->getForm()->getValues();
        $this->template->countUsers = isset($button->getForm()->getHttpData()['first_name'])?count($button->getForm()->getHttpData()['first_name']):1;
        $this->template->form_data = $button->getForm()->getHttpData();
        $session = $this->getSession();
        $section = $session->getSection('registation-');
        $section->set('countUsers',$this->template->countUsers);
        $section->set('form_data',$this->template->form_data);

        $this->redrawControl('tabulkaContainer');
    }


    protected function createComponentRaceUserForm()
    {
        $form = new Form;
        $form->getElementPrototype()->class('ajax');

        $form->addSubmit('doplnit', 'doplnit')->setAttribute('class','btn-warning')
            ->onClick[] = [$this, 'doplnitForm'];

        $form->addSubmit('send', '<i class="fas fa-paper-plane"></i> Odeslat protokol');
        $this->makeBootstrap5($form);

        $form->onSuccess[] = [$this, 'raceUserFormSucceeded'];
        return $form;
    }

    public function raceUserFormSucceeded($form, $values)
    {
        $values = $form->getHttpData();
        $session = $this->getSession();
        $section = $session->getSection('registation-');

        if ($form->isSubmitted()->getName() == 'send') {

            if ($values['sdh'][0] == "" ) {
                $this->flashMessage('Nedadal jsi SDH', 'danger');
                $section->remove('countUsers');
                $section->remove('form_data');
                $this->redirect(':default');

            }
            if ($values['first_name'][0] == "") {
                $this->flashMessage('Nedadal jsi prvního závodníka', 'danger');
                $section->remove('countUsers');
                $section->remove('form_data');
                $this->redirect(':default');

            }
            if ($values['email_sum'][0] == "") {
                $this->flashMessage('Nedadal jsi email', 'danger');
                $section->remove('countUsers');
                $section->remove('form_data');
                $this->redirect(':default');
            }

            $hash = bin2hex(random_bytes(16));

            if (isset($values['hash_old']) && $values['hash_old'] != null && $values['hash_old'] != ""){
                $hash = $values['hash_old'];
            }
            $email_text = "potvrzujeme registrace závodníků na závod Uzlovačka 2023 v Neplachovicích.<br>Seznam zaregistrovaných:<br>";

            for($i = 0;$i < 100;$i++){
                if (!isset($values['first_name'][$i])) break;
                if ($values['first_name'][$i] == "") break;
                $tmp = array(
                    'first_name'=>$values['first_name'][$i],
                    'last_name'=>$values['last_name'][$i],
                    'year'=>$values['year'][$i],
                    'first_time'=>$values['first_time'][$i],
                    'sdh'=>$values['sdh'][0],
                    'category'=>$values['category_id'][$i],
                    'hash' => $hash
                );
                $email_text.= $values['first_name'][$i]." ".$values['last_name'][$i]."<br>";

                try {
                    bdump($tmp);
                    $this->competitorManager->addUser($tmp);
                    $this->flashMessage('Zaregistrováno', 'success');
                } catch (Nette\Security\AuthenticationException $e) {
                    $form->addError($e->getMessage());
                }
            }

            $email_text .= "<br><br> <h3>V případě jakýchkoliv změn nás prosím kontaktujte.</h3>";
            $email_text .= "<br> SDH Neplachovice - Hloušek Matěj 731882688<br>";

            SendEmailManager::sendEmail($values['email_sum'][0],"Potvrzení registrace","Potvrzení registrace",$email_text,$hash);




            $section->remove('countUsers');
            $section->remove('form_data');
            $section->set('hash',$hash);

            $this->redirect(':default',$hash);
        }
    }

}

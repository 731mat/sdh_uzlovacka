<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use App\Model\EmailManager\SendEmailManager;
use App\Model\Race\RaceCategoryManager;
use App\Model\Race\RaceListManager;
use App\Model\Race\RaceUserManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\ArrayHash;


final class RacePresenter extends BasePresenter
{

    private RaceListManager $raceListManager;
    private RaceUserManager $raceUserManager;
    private RaceCategoryManager $raceCategoryManager;

    public function __construct(RaceListManager $raceListManager,RaceUserManager $raceUserManager,RaceCategoryManager $raceCategoryManager)
    {
        parent::__construct();
        $this->raceListManager = $raceListManager;
        $this->raceUserManager = $raceUserManager;
        $this->raceCategoryManager = $raceCategoryManager;
    }
    public function renderDefault(){
        $this->redirect('Homepage:default');
    }
    public function renderDetail($id){
        $this->template->race = $this->raceListManager->getRace($id);
    }
    public function renderRegistration($id){
        $this->template->race = $this->raceListManager->getRace($id);
        if ($this->template->race == null)  $this->redirect('Homepage:default');



        $session = $this->getSession();
        $section = $session->getSection('registation-'.$id);
        $countUsers = $section->get('countUsers');
        $form_data = $section->get('form_data');
        $usersToPay = $section->get('usersToPay');

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

        $this->template->categorys = $this->raceCategoryManager->getCategoryByRace($id);
        $this->template->categorysAsc = $this->raceCategoryManager->getCategoryByRaceAsc($id);
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
        $section = $session->getSection('registation-'.$button->getForm()->getHttpData()['id_race']);
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

        if ($form->isSubmitted()->getName() == 'send') {
            $hash = bin2hex(random_bytes(16));

            $usersToPay = [];
            for($i = 0;$i < 100;$i++){
                if (!isset($values['first_name'][$i])) break;
                $tmp = array(
                    'first_name'=>$values['first_name'][$i],
                    'last_name'=>$values['last_name'][$i],
                    'date_birth'=>$values['date_birth'][$i],
                    'team'=>$values['team'][$i],
                    'email'=>$values['email'][$i],
                    'phone'=>$values['phone'][$i],
                    'id_race_category'=>$values['category_id'][$i],
                    'id_race'=>$values['id_race'],
                    'pay'=>0,
                    'hash' => $hash
                );
                $usersToPay[] = $tmp;
                try {
                    $this->raceUserManager->addUser($tmp);
                    $this->flashMessage('Editováno', 'success');
                } catch (Nette\Security\AuthenticationException $e) {
                    $form->addError($e->getMessage());
                }
            }

            bdump($usersToPay);

            $session = $this->getSession();
            $section = $session->getSection('registation-'.$values['id_race']);
            $section->remove('countUsers');
            $section->remove('form_data');
            $section->set('hash',$hash);

            $this->redirect(':registratioCompleted',$hash);
        }
    }


}

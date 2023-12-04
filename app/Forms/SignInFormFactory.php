<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;

	private FormFactory $factory;

	private User $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Please enter your password.');


		$form->addSubmit('send', 'Přihlásit se');
		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
			try {
				$this->user->setExpiration('14 days');
				$this->user->login($data->username, $data->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('The username or password you entered is incorrect.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}

}

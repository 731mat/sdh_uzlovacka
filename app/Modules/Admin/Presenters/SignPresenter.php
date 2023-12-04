<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Components\SystemVariable\SystemVariableControl;
use App\Components\SystemVariable\SystemVariableFactory;
use App\Forms;
use Nette;
use Nette\Application\UI\Form;


final class SignPresenter extends Nette\Application\UI\Presenter
{
	/** @persistent */
	public string $backlink = '';

	private Forms\SignInFormFactory $signInFactory;

	private Forms\SignUpFormFactory $signUpFactory;


	public function __construct(Forms\SignInFormFactory $signInFactory, Forms\SignUpFormFactory $signUpFactory)
	{
		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
	}


    /** @var SystemVariableFactory @inject */
    public $systemVariableFactory;

    protected function createComponentSystemVariable(): SystemVariableControl
    {
        return $this->systemVariableFactory->create();
    }
	/**
	 * Sign-in form factory.
	 */
	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function (): void {
			$this->restoreRequest($this->backlink);
            $_SESSION['RF']["verify"] = "a0e7834237e0be7c0dafb0311fdb5f12";
            $this->redirect('Dashboard:');
		});
	}


	/**
	 * Sign-up form factory.
	 */
	protected function createComponentSignUpForm(): Form
	{
		return $this->signUpFactory->create(function (): void {
			$this->redirect('Dashboard:');
		});
	}


	public function actionOut(): void
	{
		$this->getUser()->logout();
	}
}

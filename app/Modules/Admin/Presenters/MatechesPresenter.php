<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\MatchesManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


final class MatechesPresenter extends BasePresenter
{

    private MatchesManager $matchesManager;

    public function __construct(MatchesManager $matchesManager)
    {
        parent::__construct();
        $this->matchesManager = $matchesManager;
    }

    public function actionGenerateRound($id){
        $this->matchesManager->generateRound($id);
        $this->redirect(":default");
    }
    public function renderDefault()
    {
        $this->template->matches = $this->matchesManager->getMatches();
    }

}

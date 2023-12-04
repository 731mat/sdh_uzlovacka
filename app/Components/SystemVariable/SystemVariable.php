<?php declare(strict_types=1);

namespace App\Components\SystemVariable;

use App\Model\ListManager;
use App\Model\SystemVariableManager;
use Latte\Runtime\Template;
use Nette\Application\UI\Control;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Localization\ITranslator;

class SystemVariableControl extends Control
{
    /** @var SystemVariableManager */
    private SystemVariableManager $SystemVariableManager;

    public function __construct(SystemVariableManager $SystemVariableManager)
    {
        $this->SystemVariableManager = $SystemVariableManager;
    }

    public function render($id): void
    {
        $menuList = $this->SystemVariableManager->getVariable($id);

        if (!$menuList) echo "Nenastaveno";
        if (isset($menuList['val'])) echo $menuList['val'];
    }
}
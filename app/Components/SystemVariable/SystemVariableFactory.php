<?php

declare(strict_types=1);

namespace App\Components\SystemVariable;

use App\Model\SystemVariableManager;

class SystemVariableFactory
{
    /** @var SystemVariableManager */
    private SystemVariableManager $SystemVariableManager;

    public function __construct(SystemVariableManager $SystemVariableManager)
    {
        $this->SystemVariableManager = $SystemVariableManager;
    }

    public function create(): SystemVariableControl
    {
        return new SystemVariableControl($this->SystemVariableManager);
    }
}
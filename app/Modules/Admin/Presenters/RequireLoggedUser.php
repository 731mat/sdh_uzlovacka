<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;


trait RequireLoggedUser
{
	public function injectRequireLoggedUser(): void
	{
		$this->onStartup[] = function () {

		};
	}
}

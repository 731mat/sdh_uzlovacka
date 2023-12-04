<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;


	public static function createRouter(): RouteList
	{
		$router = new RouteList;

        $router->withModule('Admin')
        ->addRoute('admin/race-list/<raceid>/users/<action>[/<id>]','RaceUser:default')
        ->addRoute('admin/race-list/<raceid>/category/<action>[/<id>]','RaceCategory:default')
        ->addRoute('admin/<presenter>/<action>[/<id>]','Dashboard:default');
        $router->withModule('Front')
            ->addRoute('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}
}

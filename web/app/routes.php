<?php

	define('DS', DIRECTORY_SEPARATOR, true);
	define('BASE_PATH', __DIR__ . DS, TRUE);

	$app            = System\App::instance();
	$app->request   = System\Request::instance();
	$app->route     = System\Route::instance($app->request);
	$route          = $app->route;


	$route->get('/', '\Web\App\Controllers\IndexC@index')->as('index');
	$route->get('/logs', '\Web\App\Controllers\IndexC@logs')->as('logs');
	$route->get('/ajax_current', '\Web\App\Controllers\IndexC@ajax_current')->as('ajax_current');



    $route->end();
<?php

namespace Web\App\Controllers;

use Philo\Blade\Blade;

session_start();

class Controller
{
	public $blade;

    private $views = BASE_PATH . '/Views';
    private $cache = BASE_PATH . '/Views/cache';

	public function __construct()
    {
        $this->blade = new Blade($this->views, $this->cache);
    }

}
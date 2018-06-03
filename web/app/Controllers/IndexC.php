<?php

namespace Web\App\Controllers;

use Web\App\Models\Current;
use Web\App\Models\Currency;
use Web\App\Models\Debit;

class IndexC extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    protected $currencies;

    public function index()
    {
        echo $this->blade->view()->make('index', ['currencies' => 'test'])->render();
    }

}

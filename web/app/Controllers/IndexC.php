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

    /**
     * Головна сторінка
     */
    public function index()
    {
        $currents = \Models\Current::all()->map(
            function ($item) {
                $online = false;
                $updated_at = StrToTime ( $item->updated_at );
                $now = StrToTime ( 'now' );
                $diff_seconds = ($now - $updated_at);
                if($diff_seconds <= 30) {
                    $online = true;
                }
                $item->online = $online;
                return $item;
            }
        );
        echo $this->blade->view()->make('index', ['currents' => $currents])->render();
    }


    public function ajax_current()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        //генерация числа
        $new_data = rand(0, 1000);
        //вывод числа
        echo "data: Новое число: $new_data";
        flush();
    }

    public function logs()
    {
        echo $this->blade->view()->make('logs')->render();
    }

}

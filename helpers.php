<?php

const API_RATE = 'https://api.cryptonator.com/api/ticker/';

if (!function_exists('dd')) {
    /**
     * damp and die
     * @param $arg
     */
    function dd($arg)
    {
        print_r($arg);
        echo PHP_EOL;
        die();
    }
}

if (!function_exists('getRate')) {
    /**
     * Получаем курс
     * @param $code
     * @return int|null
     */
    function getRate($code)
    {
        if($code === 'USD') {
            return 1;
        } else {
            $curr = json_decode(file_get_contents(API_RATE . strtolower(trim($code)) . '-usd'));
            if($curr->success) {
                return $curr->ticker->price;
            } else {
                return null;
            }
        }
    }
}

if (!function_exists('price_format')) {
    /**
     * форматирование цены
     * @param $number
     * @return string
     */
    function price_format($number)
    {
        return number_format($number, 2, '.', ' ');
    }
}
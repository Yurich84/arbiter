<?php

const API_RATE = 'https://api.cryptonator.com/api/ticker/';

function dd($arg)
{
	print_r($arg);
	echo PHP_EOL;
	die();
}

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
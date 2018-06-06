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
        $currency = strtolower(trim($code));
        if($currency === 'usd') {
            $rate = 1;
        } else {

            $rate = null;

            if( $RATE = \Models\Rate::firstOrNew(['currency' => $currency]) ) {
                $updated_at = StrToTime ( $RATE->updated_at );
                $now = StrToTime ( 'now' );
                $diff_hours = ($now - $updated_at) / ( 60 * 60 );
                $rate = $RATE->price;
            } else {
                $diff_hours = 9999;
            }

            if($diff_hours > 12) {
                $curr = json_decode(file_get_contents(API_RATE . $currency . '-usd'));
                $rate = $curr->ticker->price;

                // пишем в базу
                $RATE->currency = $currency;
                $RATE->price = $rate;
                $RATE->updated_at = (new DateTime())->format('Y-m-d H:i:s');
                $RATE->save();

            }

        }
        return $rate;
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
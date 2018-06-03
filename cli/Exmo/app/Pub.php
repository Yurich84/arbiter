<?php

namespace Cli\Exmo\App;

class Pub {

    const API_URL = 'https://api.exmo.com/v1/';

     function __construct($circle) {

         $this->order_book = $this->getOrderBook($circle);
     }

     public $order_book;

    /**
     * Отримуєм список ставвок по потрібним карам
     * @param $circle
     * @return mixed
     */
     public function getOrderBook($circle)
     {
         $pairs = $circle->pair1[0].'_'.$circle->pair1[2]
             .','.$circle->pair2[0].'_'.$circle->pair2[2]
             .','.$circle->pair3[0].'_'.$circle->pair3[2];
         return json_decode(file_get_contents(self::API_URL . 'order_book/?pair=' . $pairs . '&limit=1'));
     }

    /**
     * Получаем последнюю цену по которой хотят продать/купить
     * @param $pair
     * @return mixed
     */
    public function getOrder($pair)
    {
        $type = '=>' ? 'ask' : '<=' ? 'bid' : '';

        return current($this->order_book->{$pair[0].'_'.$pair[2]}->{$type});
    }

}
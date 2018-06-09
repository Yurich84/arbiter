<?php

namespace Cli\Exmo\App;

class Pub {

    const API_URL = 'https://api.exmo.com/v1/';

     function __construct($circle) {
         $this->koef = pow((1 - FEE), 3);
         $this->order_book = $this->getOrderBook($circle);
     }

     public $order_book;
     public $koef;

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
         if($contents = @file_get_contents(self::API_URL . 'order_book/?pair=' . $pairs . '&limit=1')){
             return json_decode($contents);
         } else {
             return false;
         }
     }

    /**
     * Получаем последнюю цену по которой хотят продать/купить
     * @param $pair
     * @param $direction
     * @return mixed
     */
    public function getOrder($pair, $direction)
    {
        if($this->order_book) {
            return $this->order_book->{$pair[0].'_'.$pair[2]};
        } else {
            return false;
        }

    }


    /**
     * прораховужмо профіт
     * @param $circle
     * @param $revers
     * @return object|bool
     */
    public function getProfit($circle, $revers = false)
    {
        $pow = 1;
        $order = [];
        $get_order = [];
        $i = 0;
        foreach ($circle as $pair) {
            if($revers) {
                $pow = 1;
                $direction = ($pair[1] == '=>') ? '<=' : '=>';
            } else {
                $pow = -1;
                $direction = $pair[1];
            }

            $type = ($direction == '=>') ? 'bid' : 'ask';

            $order[$i] = [];
            if ($get_order[$i] = $this->getOrder($pair, $direction)) {
                $order[$i]['amount'] = getRate($pair[0]) * (float)current($get_order[$i]->{$type})[1]; // мінімальная ставка USD
                $order[$i]['price'] = current(current($get_order[$i]->{$type}));
                $order[$i]['ask'] = json_encode(current($get_order[$i]->ask));
                $order[$i]['bid'] = json_encode(current($get_order[$i]->bid));
                $order[$i]['direction'] = $direction;
                $order[$i]['pow'] = ($pair[1] == '=>') ? -1 : 1;
            }

            $i++;
        }

        if (isset($order[0]['price']) && isset($order[1]['price']) && isset($order[2]['price'])) {
            $min = round(min($order[0]['amount'], $order[1]['amount'], $order[2]['amount']), 2); // минимальна сума

            // ФОРМУЛА
            $percent = round(
                ($this->koef
                * pow($order[0]['price'], $order[0]['pow']*$pow)
                * pow($order[1]['price'], $order[1]['pow']*$pow)
                * pow($order[2]['price'], $order[2]['pow']*$pow)
                * 100 - 100),
                4);

            if($revers) {
                $a = 1;
                $b = 5;
                $c = 2;
            } else {
                $a = 1;
                $b = 2;
                $c = 7;
            }


            return (object) [
                'percent' => $percent,
                'min' => $min,
                'prices' => [
                    $a => (object) [
                        'pair' => $circle->pair1[0] . '_' . $circle->pair1[2],
                        'direction' => $order[0]['direction'],
                        'ask' => $order[0]['ask'],
                        'bid' => $order[0]['bid'],
                        'price' => $order[0]['price'],
                    ],
                    $b => (object) [
                        'pair' => $circle->pair2[0] . '_' . $circle->pair2[2],
                        'direction' => $order[1]['direction'],
                        'ask' => $order[1]['ask'],
                        'bid' => $order[1]['bid'],
                        'price' => $order[1]['price'],
                    ],
                    $c => (object) [
                        'pair' => $circle->pair3[0] . '_' . $circle->pair3[2],
                        'direction' => $order[2]['direction'],
                        'ask' => $order[2]['ask'],
                        'bid' => $order[2]['bid'],
                        'price' => $order[2]['price'],
                    ]
                ]
            ];
        } else {
            return false;
        }

    }

}
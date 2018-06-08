<?php

namespace Cli\Exmo\App;

use Models\Current;
use Models\Log;
use Models\Order;

class Arbiter
{

    function __construct($circle)
    {
        $this->circle = $circle;
        $this->colors = new \Colors();
    }

    public $circle;

    /**
     * @return Pub
     */
    protected $colors;

    /**
     * Запускаємо бота
     */
    public function run()
    {
        while(true) {
            $this->circle();
            sleep(TIMEOUT);
        }
    }

    /**
     * зациклюем
     */
    protected function circle()
    {
        $PUB = new Pub($this->circle);

        $profit_R = $PUB->getProfit($this->circle);
        $profit_L = $PUB->getProfit($this->circle, true);

        if($profit_R && $profit_L) {
            if( $profit_R->percent > $profit_L->percent ) {
                $profit = $profit_R->percent;
                $min = $profit_R->min;
            } else {
                $profit = $profit_L->percent;
                $min = $profit_L->min;
            }

            $this->log($profit, $min);

            /*------------------------------------------------------
             * якщо профіт більше заданого то виставляємо три ордери
             * і проводимо моментальні операції
             */
            if($profit > DEAL_PERCENT && $min > DEAL_MIN_AMONG) {

                if( $profit_R->percent > $profit_L->percent ) {
                    echo PHP_EOL. ' Торгуемо за годинниковою стр. на ' . DEAL_AMONG . ' USD ' . PHP_EOL;
                    $this->makeOrder($profit_R);
                } else {
                    echo PHP_EOL. ' Торгуемо проти годинникової стр. на ' . DEAL_AMONG . ' USD ' . PHP_EOL;
                    $this->makeOrder($profit_L, true);
                }

            }
        }

    }


    /**
     * @param $profit
     * @param bool $revers
     */
    public function makeOrder($profit, $revers = false)
    {
        $auth = new Auth(KEY, SECRET);

        $log_id = Log::whereRaw('id = (select max(`id`) from logs)')->first()->id;

        $comment = '';
        $deals = [];
        if($revers) {
            ksort($profit->prices);
        }
//        dd($profit->prices);
        $prev_among = 0;
        foreach ($profit->prices as $order) {

            // напрямок сдєлкі
            if ($order->direction == '=>') {
                $type = 'sell';
                $sdelka = ' продаємо i отримуємо ';
                $pow = 1;
            } else {
                $type = 'buy';
                $sdelka = ' купуємо за ';
                $pow = -1;
                $prev_among = $prev_among / $order->price;
            }


            if($prev_among > 0) {
                $among = $prev_among;
            } else {
                $among_usd = min(DEAL_AMONG, $profit->min);
                $among = $among_usd / getRate(current(explode('_', $order->pair)));
            }
            $deals[] = [
                "pair" => $order->pair,
                "quantity" => $among,
                "price" => $order->price,
                "type" => $type
            ];

            if ($order->direction == '=>') {
                $prev_among = $among * pow($order->price, $pow);
            } else {
                $prev_among = $among;
            }

            $among *= (1 - FEE); // враховуємо комісію

            $comment .= $among . ' ' . explode('_', $order->pair)[0] . $sdelka . $among . '*' . $order->price . ' = ' . $among * $order->price . ' ' . explode('_', $order->pair)[1] . '<br />';

            // план: подсчет заработаного за цикл
        }


        // проводимо три сдєлкі
        if($deals[0] && $deals[1] && $deals[2]) {

            foreach ($deals as $deal) {
                if(GO) {
                    $auth->query("order_create", $deal);
                }

                // запис в базу ордера
                $order = $deal;
                $order['among'] = $deal['quantity'];
                $order['log_id'] = $log_id;
                unset($order['quantity']);

                Order::create($order);

            }

            Log::find($log_id)->update(['comment' => $comment]);

        }


    }


    /**
     * Виводимо і логіруємо статистику
     * @param $percent
     * @param $min
     */
    protected function log($percent, $min)
    {
        $cf = [
            $this->circle->pair1[0],
            $this->circle->pair1[2],
            $this->circle->pair2[0],
            $this->circle->pair2[2],
            $this->circle->pair3[0],
            $this->circle->pair3[2],
        ];
        $trio = implode('_', array_unique($cf));

        $add_text = '';
        $color = 'cyan';
        $bg_color = null;

        if($percent > 0.2) {
            $add_text = ' - min ' . $min . ' USD';
            $color = 'cyan';
            $bg_color = 'red';
            // пишем в базу
            Log::create([
                'broker' => EXCHANGE,
                'trio' => $trio,
                'percent' => $percent,
                'min_order' => $min,
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
        }

        // пишем в таблицу currents текущее состояние бота
        $current = Current::firstOrNew(['broker' => EXCHANGE, 'trio' => $trio,]);
        $current->percent = $percent;
        $current->min_order = $min;
        $current->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
        $current->save();

        echo "\033[70D";      // Move 5 characters backward
        echo str_pad($this->colors->getColoredString('Доход арбитража ' . $trio . ' ' . $percent . ' % ' . $add_text, $color, $bg_color), 70, ' ', STR_PAD_LEFT);
    }

}
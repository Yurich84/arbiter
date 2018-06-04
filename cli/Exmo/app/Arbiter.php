<?php

namespace Cli\Exmo\App;

use Models\Log;

class Arbiter
{

    function __construct($circle)
    {
        $this->circle = $circle;
        $this->colors = new \Colors();
    }

    public $circle;
    protected $model;
    protected $colors;

    /**
     * Запускаємо бота
     */
    public function run()
    {
        while(true) {
            $this->model = new Pub($this->circle);
            $this->circle();
        }
    }

    /**
     * зациклюем
     */
    protected function circle()
    {
        $order1 = $this->model->getOrder($this->circle->pair1);
        $order1_amount = getRate($this->circle->pair1[0]); // мінімальная ставка USD
        $order1_price = current( $order1 );


        $order2 = $this->model->getOrder($this->circle->pair2);
        $order2_amount = getRate($this->circle->pair2[0]); // мінімальная ставка USD
        $order2_price = current( $order2 );


        $order3 = $this->model->getOrder($this->circle->pair3);
        $order3_amount = getRate($this->circle->pair3[0]); // мінімальная ставка USD
        $order3_price = current( $order3 );


        if($order1 && $order2 && $order3) {
            $min = round(min($order1_amount, $order2_amount, $order3_amount ), 2); // минимальна сума
            $koef = pow((1 - FEE), 3);
            $profit = round( ($koef * $order1_price * $order2_price / $order3_price * 100  - 100), 4);

            $this->log($profit, $min);

            // план : якщо профіт більше заданого то проводимо виставляємо три ордери і проводимо моментальні операції
            if($profit > DEAL_PERCENT && $min > DEAL_MIN_AMONG) {
                echo PHP_EOL. ' Торгуемо на ' . DEAL_AMONG . ' USD ' . PHP_EOL;
            }

            sleep(TIMEOUT);
        }

    }


    /**
     * Виводимо і логіруємо статистику
     * @param $profit
     * @param $min
     */
    protected function log($profit, $min)
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
        if($profit > 0) {
            $color = 'red';
            $add_text = ' - min ' . $min . ' USD';
            if($profit > 0.2) {
                $color = 'cyan';
                $bg_color = 'red';
                // пишем в базу
                Log::create([
                    'exchange' => EXCHANGE,
                    'trio' => $trio,
                    'profit' => $profit,
                    'min_order' => $min,
                    'created_at' => (new DateTime())->format('Y-m-d H:i:s')
                ]);
            }
        }
        echo "\033[50D";      // Move 5 characters backward
        echo str_pad($this->colors->getColoredString('Доход арбитража ' . $trio . ' ' . $profit . ' % ' . $add_text, $color, $bg_color), 50, ' ', STR_PAD_LEFT);
    }

}
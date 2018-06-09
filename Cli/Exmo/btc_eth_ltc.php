<?php
require_once __DIR__ . '/Boot.php';

$circle = (object) [
    'pair1' => ['ETH', '<=', 'BTC'], // покупаем ETH за биткоини
    'pair2' => ['ETH', '=>', 'LTC'], // продаем ETH за LTC
    'pair3' => ['LTC', '=>', 'BTC'], // продаем LTC за BTC
];

$arbiter = new \Cli\Exmo\App\Arbiter($circle);
$arbiter->run();
<?php
require_once __DIR__ . '/Boot.php';

$circle = (object) [
    'pair1' => ['BTC', '<=', 'USD'], // покупаем биткоин за доллари
    'pair2' => ['ETH', '<=', 'BTC'], // покупаем ETH за биткоини
    'pair3' => ['ETH', '=>', 'USD'], // продаем ETH за доллари
];

$arbiter = new \Cli\Exmo\App\Arbiter($circle);
$arbiter->run();
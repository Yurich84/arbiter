<?php
require_once __DIR__ . '/Boot.php';

$circle = (object) [
    'pair1' => ['BTC', '<=', 'USD'], // покупаем биткоин за доллари
    'pair2' => ['ZEC', '<=', 'BTC'], // покупаем ZEC за биткоини
    'pair3' => ['ZEC', '=>', 'USD'], // продаем ZEC за доллари
];

$arbiter = new \Cli\Exmo\App\Arbiter($circle);
$arbiter->run();
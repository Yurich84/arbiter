<?php
require_once __DIR__ . '/Boot.php';

$circle = (object) [
    'pair1' => ['BTC', '<=', 'USD'], // покупаем биткоин за доллари
    'pair2' => ['LTC', '<=', 'BTC'], // покупаем LTC за биткоини
    'pair3' => ['LTC', '=>', 'USD'], // продаем LTC за доллари
];

$arbiter = new \Cli\Exmo\App\Arbiter($circle);
$arbiter->run();
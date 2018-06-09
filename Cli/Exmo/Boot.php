<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
    require_once dirname(dirname(__DIR__)) . '/config.php';
    require_once dirname(__DIR__) . '/Colors.php';
    require_once __DIR__ . '/config.php';

    $mask = "|%'.-20s|%-10s |\n";
    echo '+--------------------------------+' . PHP_EOL;
    printf($mask, 'Broker ', EXCHANGE);
    printf($mask, 'Time interval ', TIMEOUT . ' c');
    printf($mask, 'Percent ', DEAL_PERCENT . ' %');
    printf($mask, 'Min among ', DEAL_MIN_AMONG . ' USD');
    printf($mask, 'Max among ', DEAL_AMONG . ' USD');
    printf($mask, 'Go ', (GO ? 'On' : 'Off'));
    echo '+--------------------------------+' . PHP_EOL;
<?php

require_once __DIR__ . '/vendor/autoload.php';

$helper = Veridu\Helper\API::factory('demo', '0123456789', 'flavio');

$helper->setUpUser('916f2f7e5be6cfe46f5029d61be705362de06cf8');

$helper->getProfile();

$helper->sendToken('twitter', 'token', 'secret');

$helper->sendToken('facebook', 'token');

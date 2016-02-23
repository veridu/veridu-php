<?php

require_once __DIR__ . '/vendor/autoload.php';

$api = Veridu\API::factory('demo', '0123456789', 'flavio');

$api->session->create();
$api->user->create('916f2f7e5be6cfe46f5029d61be705362de06cf8');


// USER

echo 'PROFILE', PHP_EOL;
$json = $api->user->getAllDetails();
print_r($json);

echo 'ATTRIBUTE VALUES', PHP_EOL;
$json = $api->user->getAllAttributeValues();
print_r($json);

echo 'ATTRIBUTE SCORES', PHP_EOL;
$json = $api->user->getAllAttributeScores();
print_r($json);

echo 'NAME', PHP_EOL;
$json = $api->user->attributeDetails('name');
print_r($json);

echo 'NAME VALUE', PHP_EOL;
$nameValue = $api->user->attributeValue('name');
echo $nameValue, PHP_EOL;

echo 'NAME SCORE', PHP_EOL;
$nameScore = $api->user->attributeScore('name');
echo $nameScore, PHP_EOL;

echo 'NAME COMPARE', PHP_EOL;
$json = $api->user->compareAttribute('name', 'Flávio Heleno');
print_r($json);

// PROVIDER

// echo 'OAUTH1', PHP_EOL;
// $taskId = $api->provider->createOAuth1('twitter', '');
// echo $taskId, PHP_EOL;

// echo 'OAUTH2', PHP_EOL;
// $taskId = $api->provider->createOAuth2('facebook', 'CAAEO02ZBeZBwMBAOAGqYEzGYi2jTfwKd5Jk67kt4GKTkjeepIMh8EQH344v1A0KeC7Jfs1PoC0Mw7gQGJnpoZAejTwPpk7Sk1JOSfPSeXHRhj9j7emoc3TYasi5OzkKVMZASlGQQ0UZCTC44WifU0Wm5qPUw1f9O0ZAkBwFwpOT8qiT8ZBcsiWMpcwxoZBbFgV1lQgZB90oscEDUIWngZB3hKb');
// echo $taskId, PHP_EOL;

echo 'PROVIDER LIST', PHP_EOL;
$json = $api->provider->listAll();
print_r($json);

echo 'FACEBOOK', PHP_EOL;
$facebookCheck = $api->provider->check('facebook');
echo ($facebookCheck ? 'true' : 'false'), PHP_EOL;

// PROFILE

echo 'PROFILE', PHP_EOL;
$json = $api->profile->retrieve();
print_r($json);

// OTP

echo 'OTP LIST', PHP_EOL;
$json = $api->otp->listAll();
print_r($json);

echo 'EMAIL', PHP_EOL;
$emailCheck = $api->otp->verifiedEmail();
echo ($emailCheck ? 'true' : 'false'), PHP_EOL;

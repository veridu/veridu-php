<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$api = Veridu\API::Factory(
	'demo',
	'0123456789',
	'flavio'
);

$api->session->create(false);
$api->user->create('sdk-testUser01');
print_r($api->provider->createOAuth2('facebook', 'CAAEO02ZBeZBwMBAOAGqYEzGYi2jTfwKd5Jk67kt4GKTkjeepIMh8EQH344v1A0KeC7Jfs1PoC0Mw7gQGJnpoZAejTwPpk7Sk1JOSfPSeXHRhj9j7emoc3TYasi5OzkKVMZASlGQQ0UZCTC44WifU0Wm5qPUw1f9O0ZAkBwFwpOT8qiT8ZBcsiWMpcwxoZBbFgV1lQgZB90oscEDUIWngZB3hKb'));

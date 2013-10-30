<?php
/*
* Session management
*
* This example demonstrates how to handle a read/write session, for logged in users
*/

//using composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

//using distribution autoloader
//$autoloader = require_once __DIR__ . '/autoload.php';
//$autoloader->setIncludePath(__DIR__ . '/../src/');

//requiring client configuration
require_once __DIR__ . '/settings.php';

//logged in user ID
$user_id = 'some-system-unique-user-id';

try {
	session_start();
	//Session SDK instantiation
	$session = new Veridu\SDK\Session(
		new Veridu\SDK\API(
			new Veridu\Common\Config(
				$config['client'],
				$config['secret'],
				$config['version']
			),
			new Veridu\HTTPClient\CurlClient,
			new Veridu\Signature\HMAC
		)
	);

	//cache check / expire check
	if ((empty($_SESSION['veridu']['expires'])) || ((intval($_SESSION['veridu']['expires']) - time()) <= 0)) {
		/*
			A Veridu session wasn't found or it was already expired, so create a new one
		*/

		//creates new a read/write Veridu session
		$session->create(false);
		//assigns the fresh Veridu session to currently logged in user
		$session->assign($user_id);
		//stores Veridu's session information on cache for later use
		$_SESSION['veridu'] = array(
			'session' => $session->getToken(),
			'expires' => $session->getExpires()
		);
	} else {
		/*
			A Veridu session was found and it's still valid
		*/

		//sets the session token to the previous created token
		$session->setToken($_SESSION['veridu']['session']);
		//sets the user identification to the current user
		//note that this MUST be the same user that the session was assigned to
		$session->setUsername($user_id);

		//checks if Veridu's session will expire in less than a minute
		if ((intval($_SESSION['veridu']['expires']) - time()) < 60) {
			//extends Veridu's session lifetime
			$session->extend();
			//stores new expiration unixtime for later use
			$_SESSION['veridu']['expires'] = $session->getExpires();
		}
	}
} catch (Exception $exception) {
	//error with session setup
	printf("Error with session setup: %s\n", $exception->getMessage());
	exit;
}
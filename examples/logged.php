<?php


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/settings.php';

try {
	session_start();
	//api object creation
	$api = new Veridu\SDK\API(
		$config['client'],
		$config['secret'],
		$config['version'],
		new Veridu\HTTPClient\CurlClient,
		new Veridu\Signature\HMAC(
			$config['client'],
			$config['secret'],
			$config['version']
		)
	);

	//cache check / expire check
	if ((empty($_SESSION['veridu']['expires'])) || ((intval($_SESSION['veridu']['expires']) - time()) <= 0)) {
		$api->sessionCreate(false);
		$api->userCreate('user-unique-id');
		$_SESSION['veridu'] = array(
			'session' => $api->getSession(),
			'expires' => $api->getExpires(),
			'username' => $api->getUsername()
		);
	} else {
		$api->setSession($_SESSION['veridu']['session']);
		$api->setUsername($_SESSION['veridu']['username']);

		//extend session if it will expire in less than a minute
		if ((intval($_SESSION['veridu']['expires']) - time()) < 60) {
			$api->sessionExtend();
			$_SESSION['veridu']['expires'] = $api->getExpires();
		}
	}
} catch (Exception $exception) {
	//error with API usage
	echo $exception->getMessage();
	exit;
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>For logged in users</title>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="//assets.veridu.com/<?=$api->getVersion();?>/sdk/veridu.min.js"></script>
	</head>
	<body>
		<!-- content goes here -->
		<script type="text/javascript">
			$(function() {
				var user = '<?=$api->getUsername();?>',
					veridu = new Veridu({
						client: '<?=$api->getClient();?>',
						session: '<?=$api->getSession();?>',
						version: '<?=$api->getVersion();?>'
					});
				//code goes here
			});
		</script>
	</body>
</html>
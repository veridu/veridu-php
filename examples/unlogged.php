<?php


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/settings.php';

try {
	session_start();
	//session object creation
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
		$session->create(true);
		$_SESSION['veridu'] = array(
			'session' => $session->getToken(),
			'expires' => $session->getExpires()
		);
	} else {
		$session->setToken($_SESSION['veridu']['session']);

		//extend session if it will expire in less than a minute
		if ((intval($_SESSION['veridu']['expires']) - time()) < 60) {
			$session->extend();
			$_SESSION['veridu']['expires'] = $session->getExpires();
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
		<title>For unlogged users</title>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="//assets.veridu.com/<?=$config['version'];?>/sdk/veridu.min.js"></script>
	</head>
	<body>
		<!-- content goes here -->
		<script type="text/javascript">
			$(function() {
				var veridu = new Veridu({
					client: '<?=$config['client'];?>',
					session: '<?=$session->getToken();?>',
					version: '<?=$config['version'];?>'
				});
				//code goes here
			});
		</script>
	</body>
</html>
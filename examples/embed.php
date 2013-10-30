<?php
/*
* Embed example
*
* This example demonstrates how to embed the NemID Applet
*/

//widget response
if (isset($_GET['veridu_success'])) {
	/*
		NemID login was successful
		At this point, you might want refer to apicall.php to retrieve user information
	*/
	$html = 'User has logged in';
} else if (isset($_GET['veridu_error'])) {
	/*
		NemID Widget returned an error
	*/
	$err = urldecode($_GET['veridu_error']);
	$err = htmlentities($err);
	$html = "<strong>Error:</strong> {$err}";
} else {
	/*
		Pré-login flow
	*/

	//getting the session management
	require_once __DIR__ . '/session.php';

	//Widget SDK instantiation
	$widget = new Veridu\SDK\Widget($session->getAPI()->getConfig(), $session->getToken(), $user_id);

	//set the redirect parameter for NemID Widget (simple way)
	$redirect = sprintf('http://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);

	//generate Widget Endpoint URL for NemID
	$url = $widget->getEndpoint('nemid/widget', array('embed' => true, 'redirect' => $redirect, 'language' => 'en-us'));
	//retrieve HTTP instance
	$http = $session->getAPI()->getHTTP();
	//get Widget code
	$html = $http->GET($url);
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<?=$html;?>
	</body>
</html>
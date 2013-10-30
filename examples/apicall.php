<?php
/*
* API request
*
* This example demonstrates how to perform an API request
*/

//getting the session management
require_once __DIR__ . '/session.php';

try {
	//retrieve API SDK instance from Session SDK instance
	$api = $session->getAPI();
	//fetchs provider information (user verifications based on Social Media and Online Services
	$information = $api->fetch('GET', "provider/{$user_id}/all");
	/*
		Provider information is now available on $information
		Example:
			Array
			(
			    [status] => 1
			    [list] => Array
			        (
			            [0] => facebook
			            [1] => linkedin
			            [2] => twitter
			        )

			    [info] => Array
			        (
			            [facebook] => Array
			                (
			                    [picture] => https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yo/r/UlIqmHJ-SK.gif
			                    [overall] => 0.65
			                )

			            [linkedin] => Array
			                (
			                    [picture] => http://m.c.lnkd.licdn.com/mpr/mprx/0_FJCU17ic7na5SMBtbVhe12hzfPgzTV9t5UnX12tErnWLYY3-w0tBgu6k3jjoGjcY6V85pwhdT7
			                    [overall] => 0.81
			                )

			            [twitter] => (NULL)
			        )

			)
	*/
} catch (Exception $exception) {
	//error with API usage
	printf("Error with API usage: [%s] %s\n", $api->lastError(), $exception->getMessage());
	exit;
}
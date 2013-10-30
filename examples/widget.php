<?php
/*
* Widget example
*
* This example demonstrates how to use widgets
*/

//getting the session management and the API response
require_once __DIR__ . '/apicall.php';
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!-- Loading the jQuery Library (required by Widget Library) -->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!-- Loading the Widget Library -->
		<script type="text/javascript" src="https://assets.veridu.com/<?=$config['version'];?>/sdk/veridu-widget.js"></script>
	</head>
	<body>
		<div id="widget" style="width: 100%; height: 540px"></div>
		<script type="text/javascript">
			var //Widget instantiation
				veridu = new Veridu({
					client: '<?=$config['client'];?>',
					session: '<?=$session->getToken();?>',
					language: 'en-us',
					country: 'dk',
					version: '<?=$config['version'];?>'
				});
				<?php
					try {
						//check the number of verifications the user has performed
						if (count($information['list']) == 0) {
							//no verifications were performed, so display the verification widget
							echo "//Verification setup\n";
							echo 'var cfg = {"aot":[],"oot":[],"opt":["facebook","linkedin","paypal","amazon","twitter","google","email","sms","spotafriend","cpr","nemid"]};' . "\n";
							echo "veridu.Widget.verification($('#widget'), '{$user_id}', cfg);\n";
						} else {
							//at least one verification was done, so display the profile widget
							echo "veridu.Widget.profile($('#widget'), '{$user_id}');\n";
						}
					} catch (Exception $exception) {
						//error with API usage
						printf("Error with API usage: %s\n", $exception->getMessage());
						exit;
					}
				?>
				$(document).on('VeriduEvent', function (event, data) {
					console.log(data);
					/*
						Handling Widget Library events

						data.eventname - Main event name
						data.action - Action performed on event (not available on all events)
						data.type - Type of event (one event has more than one type)
						data.user - Target user of event
					*/
				});
		</script>
	</body>
</html>
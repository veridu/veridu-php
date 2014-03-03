<?php
/**
* Exception thrown when trying to get a Widget Endpoint without a session token set.
*/

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class EmptyWidgetSession extends Exception {
	protected $message = 'A session token is required to create a widget endpoint.';
}
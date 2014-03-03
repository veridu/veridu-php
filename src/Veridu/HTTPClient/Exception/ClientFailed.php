<?php
/**
* Exception thrown when the underlying HTTP Client fails to perform a request due to network problems, for example.
*/

namespace Veridu\HTTPClient\Exception;

use Veridu\Exception\Exception;

class ClientFailed extends Exception {
	protected $message = 'The client failed to perform the requested action.';
}
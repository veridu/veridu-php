<?php

namespace Veridu\HTTPClient\Exception;

use Veridu\Exception\Exception;

class ClientFailed extends Exception {
	protected $message = 'The client failed to perform the requested action.';
}
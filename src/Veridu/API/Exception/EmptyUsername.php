<?php
/**
* Exception thrown when an empty username is used.
*/

namespace Veridu\API\Exception;


class EmptyUsername extends \Exception {
	protected $message = 'Empty Username.';
}
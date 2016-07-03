<?php
/**
* Exception thrown when trying to get a Widget Endpoint without a username set.
*/

namespace Veridu\API\Exception;

class EmptyWidgetUsername extends \Exception
{
    protected $message = 'A username is required to create a widget endpoint.';
}

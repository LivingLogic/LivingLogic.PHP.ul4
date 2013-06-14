<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LocationException extends \Exception
{
	public $location;

	public function __construct(Exception $previous, $location)
	{
		parent::__construct("in " . $location, 0, $previous);
		$this->location = $location;
	}
}

?>

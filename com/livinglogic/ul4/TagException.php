<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class TagException extends \Exception
{
	protected $location;

	public function __construct($cause, $location)
	{
		parent::__construct("in " . $location, 0, $cause);
		$this->location = $location;
	}
}

?>
<?php

namespace com\livinglogic\ul4;

class ReturnException extends \Exception
{
	private $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}
}

?>
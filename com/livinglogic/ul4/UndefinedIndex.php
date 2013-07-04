<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class UndefinedIndex extends Undefined
{
	private $index;

	public function __construct($index)
	{
		$this->index = FunctionInt::call($index);
	}

	public function toString()
	{
		return "undefined object for index " . Utils::repr($this->index);
	}
}

?>
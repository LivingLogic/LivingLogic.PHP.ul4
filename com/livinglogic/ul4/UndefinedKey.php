<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class UndefinedKey extends Undefined
{
	private $key;

	public function __construct($key)
	{
		$this->key = $key;
	}

	public function toString()
	{
		return "undefined object for key " . Utils::repr($this->key);
	}
}

?>
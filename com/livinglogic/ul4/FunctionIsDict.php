<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsDict extends _Function
{
	/*
	public function call($context, $args)
	{
		if (count($args) == 1)
			return \com\livinglogic\ul4on\Utils::isDict($args[0]);
		throw new ArgumentCountMismatchException("function", "isdict", count($args), 1);
	}
	*/

	public function nameUL4()
	{
		return "isdict";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("obj", Signature::$required)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		return !is_null($obj) && \com\livinglogic\ul4on\Utils::isDict($obj) && !FunctionIsTemplate::call($obj);
	}
}

?>
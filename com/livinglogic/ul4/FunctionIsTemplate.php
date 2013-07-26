<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsTemplate extends _Function
{
	/*
	public function call($context, $args)
	{
		if (count($args) == 1)
			return (!is_null($args[0]) && ($args[0] instanceof InterpretedTemplate));
		throw new ArgumentCountMismatchException("function", "istemplate", count($args), 1);
	}
	*/

	public function nameUL4()
	{
		return "istemplate";
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
		return is_null($obj) && ($obj instanceof UL4Type) && "template" == $obj->typeUL4();
	}
}

?>
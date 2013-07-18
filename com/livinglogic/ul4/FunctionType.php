<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionType extends _Function
{
	public function nameUL4()
	{
		return "type";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"obj", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		if (is_null($obj))
			return "none";
		else if (is_string($obj))
			return "str";
		else if (is_bool($obj))
			return "bool";
		else if (is_int($obj) || is_long($obj))
			return "int";
		else if (is_float($obj) || is_double($obj))
			return "float";
		else if ($obj instanceof \DateTime)
			return "date";
		else if (obj instanceof UL4Type)
			return $obj->typeUL4();
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
			return "list";
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
			return "dict";
		else
			return null;
	}
}

?>
<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionType implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "type", count($args), 1);
	}

	public function getName()
	{
		return "type";
	}

	public static function _call($obj)
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
		else if ($obj instanceof Color)
			return "color";
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
			return "list";
		else if ($obj instanceof InterpretedTemplate)
			return "template";
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
			return "dict";
		else
			return null;
	}
}

?>
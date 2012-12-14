<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsDefined implements _Function
{
	public function getName()
	{
		return "isdefined";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "isdefined", count($args), 1);
	}

	public static function _call($obj)
	{
		return !($obj instanceof Undefined);
	}
}

?>
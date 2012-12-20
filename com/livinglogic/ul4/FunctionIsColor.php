<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsColor implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "iscolor", count($args), 1);
	}

	public static function _call($arg)
	{
		return (!is_null($arg) && ($arg instanceof Color));
	}

	public function getName()
	{
		return "iscolor";
	}
}

?>
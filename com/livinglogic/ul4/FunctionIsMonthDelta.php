<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsMonthDelta implements _Function
{
	public function getName()
	{
		return "ismonthdelta";
	}

	public function call($context, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::_call($args[0]);
			default:
				throw new ArgumentCountMismatchException("function", "ismonthdelta", count($args), 1);
		}
	}

	public static function _call($obj)
	{
		return !is_null($obj) && ($obj instanceof MonthDelta);
	}
}

?>
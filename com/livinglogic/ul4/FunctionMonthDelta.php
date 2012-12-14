<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionMonthDelta implements _Function
{
	public function getName()
	{
		return "monthdelta";
	}

	public function call($context, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::_call();
			case 1:
				return self::_call($args[0]);
			default:
				throw new ArgumentCountMismatchException("function", "monthdelta", count($args), 0, 1);
		}
	}

	public static function _call()
	{
		if (func_num_args() == 0)
			return new MonthDelta();
		else
		{
			$months = func_get_arg(0);
			return new MonthDelta(Utils::toInteger($months));
		}
	}
}

?>
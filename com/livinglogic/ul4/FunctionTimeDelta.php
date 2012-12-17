<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionTimeDelta implements _Function
{
	public function getName()
	{
		return "timedelta";
	}

	public function call($context, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::_call();
			case 1:
				return self::_call($args[0]);
			case 2:
				return self::_call($args[0], $args[1]);
			case 3:
				return self::_call($args[0], $args[1], $args[2]);
			default:
				throw new ArgumentCountMismatchException("function", "timedelta", count($args), 0, 3);
		}
	}

	public static function _call()
	{
		if (func_num_args() == 0)
		{
			return new TimeDelta();
		}
		elseif (func_num_args() == 1)
		{
			$days = func_get_arg(0);
			return new TimeDelta($days, 0, 0);
		}
		elseif (func_num_args() == 2)
		{
			$days = func_get_arg(0);
			$seconds = func_get_arg(1);
			return new TimeDelta($days, $seconds, 0);
		}
		elseif (func_num_args() == 3)
		{
			$days = func_get_arg(0);
			$seconds = func_get_arg(1);
			$microseconds = func_get_arg(2);
			return new TimeDelta($days, $seconds, $microseconds);
		}
	}
}

?>
<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionTimeDelta extends _Function
{
	public function nameUL4()
	{
		return "timedelta";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("days", 0,
			"seconds", 0,
			"microseconds", 0)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1], $args[2]);
	}

	public static function call()
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
<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRandRange implements UL4Call
{
	public function getName()
	{
		return "randrange";
	}

	public function callUL4($args, $kwargs)
	{
		if (count($kwargs) != 0)
			throw new KeywordArgumentsNotSupportedException($this->getName());

		switch (args.length)
		{
			case 1:
				return self::call($args[0]);
			case 2:
				return self::call($args[0], $args[1]);
			case 3:
				return self::call($args[0], $args[1], $args[2]);
			default:
				throw new ArgumentCountMismatchException("function", "randrange", count($args), 1, 3);
		}
	}

	public static function call($obj1)
	{
		if (func_num_args() == 1)
		{
			$stopObj = $obj1;
			$stop = FunctionInt::call($stopObj);
			$value = FunctionRandom::call();
			return intval($value*$stop);
		}
		else if (func_num_args() == 2)
		{
			$startObj = $obj1;
			$stopObj = func_get_arg(1);
			$start = FunctionInt::call($startObj);
			$stop = FunctionInt::call($stopObj);
			$width = $stop - $start;
			$value = FunctionRandom::call();
			return $start + intval($value * $width);
		}
		else if (func_num_args() == 3)
		{
			$startObj = $obj1;
			$stopObj = func_get_arg(1);
			$stepObj = func_get_arg(2);

			$start = FunctionInt::call($startObj);
			$stop = FunctionInt::call($stopObj);
			$step = FunctionInt::call($stepObj);
			$width = $stop - $start;
			$value = FunctionRandom::call();

			$n;
			if ($step > 0)
				$n = intval(($width + $step - 1) / $step);
			else if ($step < 0)
				$n = intval(($width + $step + 1) / $step);
			else
				throw new UnsupportedOperationException("step can't be 0 in randrange()");

			return $start + $step * intval($value * $n);
		}
	}
}

?>
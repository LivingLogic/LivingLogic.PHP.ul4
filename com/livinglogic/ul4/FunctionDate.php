<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionDate implements _Function
{
	public function getName()
	{
		return "date";
	}

	public function call($context, $args)
	{
		switch (count($args))
		{
			case 3:
				return self::_call($args[0], $args[1], $args[2]);
			case 4:
				return self::_call($args[0], $args[1], $args[2], $args[3]);
			case 5:
				return self::_call($args[0], $args[1], $args[2], $args[3], $args[4]);
			case 6:
				return self::_call($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
			case 7:
				return self::_call($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
			default:
				throw new ArgumentCountMismatchException("function", "date", count($args), 3, 7);
		}
	}

	public static function _call($year, $month, $day)
	{
		$iyear   = FunctionInt::call($year);
		$imonth  = FunctionInt::call($month);
		$iday    = FunctionInt::call($day);
		$ihour   = 0;
		$iminute = 0;
		$isecond = 0;

		if (func_num_args() >= 4)
			$ihour = FunctionInt::call(func_get_arg(3));
		if (func_num_args() >= 5)
			$iminute = FunctionInt::call(func_get_arg(4));
		if (func_num_args() >= 6)
			$isecond = FunctionInt::call(func_get_arg(5));

		$dateTime = new \DateTime();
		$dateTime->setDate($iyear, $imonth, $iday);
		$dateTime->setTime($ihour, $iminute, $isecond);

		return $dateTime;
	}
}

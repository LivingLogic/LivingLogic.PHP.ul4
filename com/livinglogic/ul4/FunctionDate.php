<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionDate extends _Function
{
	public function nameUL4()
	{
		return "date";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"year", Signature::$required,
			"month", Signature::$required,
			"day", Signature::$required,
			"hour", 0,
			"minute", 0,
			"second", 0,
			"microsecond", 0
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
	}

	public static function call($year, $month, $day)
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

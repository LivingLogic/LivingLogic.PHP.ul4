<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionHSV extends _Function
{
	/*
	public function call($context, $args)
	{
		if (count($args) == 3)
			return self::_call($args[0], $args[1], $args[2]);
		else if (count($args) == 4)
			return self::_call($args[0], $args[1], $args[2], $args[3]);

		throw new ArgumentCountMismatchException("function", "hsv", count($args), 3, 4);
	}
	*/

	public function nameUL4()
	{
		return "hsv";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("h", Signature::$required,
			"s", Signature::$required,
			"v", Signature::$required,
			"a", 1.0)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1], $args[2], $args[3]);
	}

	public static function call($arg1, $arg2, $arg3)
	{
		if (func_num_args() == 3)
			return Color::fromhsv(FunctionFloat::call($arg1), FunctionFloat::call($arg2), FunctionFloat::call($arg3));
		elseif (func_num_args() == 4)
			return Color::fromhsv(FunctionFloat::call($arg1), FunctionFloat::call($arg2), FunctionFloat::call($arg3), FunctionFloat::call(func_get_arg(3)));
	}
}

?>
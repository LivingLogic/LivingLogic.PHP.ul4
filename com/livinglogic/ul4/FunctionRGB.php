<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRGB extends _Function
{
	public function nameUL4()
	{
		return "rgb";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("r", Signature::$required,
			"g", Signature::$required,
			"b", Signature::$required,
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
			return new Color(FunctionFloat::call($arg1), FunctionFloat::call($arg2), FunctionFloat::call($arg3));
		else if (func_num_args() == 4)
			return new Color(FunctionFloat::call($arg1), FunctionFloat::call($arg2), FunctionFloat::call($arg3), FunctionFloat::call(func_get_arg(3)));
	}
}

?>
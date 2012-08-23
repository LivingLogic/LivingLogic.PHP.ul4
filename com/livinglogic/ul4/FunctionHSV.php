<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionHSV implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 3)
			return self::_call($args[0], $args[1], $args[2]);
		else if (count($args) == 4)
			return self::_call($args[0], $args[1], $args[2], $args[3]);

		throw new ArgumentCountMismatchException("function", "hsv", count($args), 3, 4);
	}

	public function getName()
	{
		return "hsv";
	}

	public static function _call($arg1, $arg2, $arg3)
	{
		if (func_num_args() == 3)
			return Color::fromhsv(Utils::toFloat($arg1), Utils::toFloat($arg2), Utils::toFloat($arg3));
		elseif (func_num_args() == 4)
			return Color::fromhsv(Utils::toFloat($arg1), Utils::toFloat($arg2), Utils::toFloat($arg3), Utils::toFloat(func_get_arg(3)));
	}
}

?>
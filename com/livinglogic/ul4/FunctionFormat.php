<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFormat extends _Function
{
	/*
	public function call($context, $args)
	{
		if (count($args) == 2)
			return self::_call($args[0], $args[1]);
		throw new ArgumentCountMismatchException("function", "format", count($args), 2);
	}
	*/

	public function nameUL4()
	{
		return "format";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"obj", Signature::$required,
			"fmt", Signature::$required,
			"lang", null
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1], $args[2]);
	}

	public static function call($obj, $formatString)
	{
		if (func_num_args() == 2)
			$locale = "en-GB";
		else if (func_num_args() == 3)
			$locale = func_get_arg(2);

		if (is_string($formatString))
		{
			if ($obj instanceof \DateTime)
			{
				return strftime($formatString, $obj->format("U"));
			}
		}
		throw new ArgumentTypeMismatchException("format({}, {})", obj, formatString);
	}
}

?>
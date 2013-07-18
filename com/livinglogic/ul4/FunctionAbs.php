<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAbs extends _Function
{
	public function nameUL4()
	{
		return "abs";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				"number", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($arg)
	{
		if (is_int($arg) || is_long($arg) || is_float($arg) || is_double($arg))
		{
			if ($arg >= 0)
				return $arg;
			else
				return -$arg;
		}
		else if (is_bool($arg))
			return $arg ? 1 : 0;
		else if ($obj instanceof UL4Abs)
			return $obj->absUL4();

		throw new\Exception("abs(" . Utils::objectType($arg) . ") not supported!");
	}
}

?>
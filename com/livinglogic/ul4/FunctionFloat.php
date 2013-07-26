<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFloat extends _Function
{
	public function nameUL4()
	{
		return "float";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("obj", 0.0)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call()
	{
		if (func_num_args() == 0)
		{
			return 0.0;
		}
		else if (func_num_args() == 1)
		{
			$obj = func_get_arg(0);

			if (is_string($obj))
				return doubleval($obj);
			else if (is_int($obj) || is_long($obj))
				return doubleval($obj);
			else if (is_bool($obj))
				return $obj ? 1.0 : 0.0;
			else if (is_float($obj) || is_double($obj))
				return $obj;
			throw new \Exception("float(" . self::objectType($obj) . ") not supported!");
		}
		throw new ArgumentCountMismatchException("function", "float", func_num_args(), 0, 1);
	}
}

?>
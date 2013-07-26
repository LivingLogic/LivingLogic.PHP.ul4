<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionInt extends _Function
{
	public function nameUL4()
	{
		return "int";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("obj", 0,
			"base", null)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1]);
	}

	public static function call()
	{
		if (func_num_args() == 0)
		{
			return 0;
		}
		else if (func_num_args() == 1)
		{
			$obj = func_get_arg(0);
			if (is_string($obj))
			{
				$obj = trim($obj);
				if (ctype_digit($obj))
					return intval($obj);
				else if ((strpos($obj, "-") === 0) || (strpos($obj, "+") === 0) && ctype_digit(mb_substr($obj, 1, \com\livinglogic\ul4on\Utils::$encoding)))
					return intval($obj);
				else
					throw new \Exception("invalid literal for int(): " . Utils::repr($obj) . "!");
			}
			else if (is_int($obj) || is_long($obj))
				return $obj;
			else if (is_bool($obj))
				return $obj ? 1 : 0;
			else if (is_float($obj) || is_double($obj))
				return intval($obj);

			throw new \Exception("int(" . self::objectType($obj) . ") not supported!");
		}
		else if (func_num_args() == 2)
		{
			$obj = func_get_arg(0);
			$obj2 = func_get_arg(1);
			if (is_string($obj))
			{
				if (is_int($obj2) || is_long($obj2))
					return intval($obj, $obj2);
			}
			throw new \Exception("int(" . self::objectType($obj) . ", " . self::objectType($obj2) . ") not supported!");
		}
		throw new ArgumentCountMismatchException("function", "int", func_num_args(), 0, 2);
	}
}

?>
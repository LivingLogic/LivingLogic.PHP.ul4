<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionBool extends _Function
{
	public function nameUL4()
	{
		return "bool";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				"obj", false
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call()
	{
		if (func_num_args() == 0)
			return false;
		else if (func_num_args() == 1)
		{
			$obj = func_get_arg(0);

			if (is_null($obj))
				return false;
			else if ($obj instanceof Undefined)
				return false;
			else if (is_bool($obj))
				return $obj;
			else if (is_string($obj))
				return strlen($obj) > 0;
			else if (is_int($obj) || is_long($obj))
				return $obj != 0;
			else if (is_float($obj) || is_double($obj))
				return $obj != 0.;
			else if ($obj instanceof \DateTime)
				return True;
			else if ($obj instanceof TimeDelta)
				return $obj->getDays() != 0 || $obj->getSeconds() != 0 || $obj->getMicroseconds() != 0;
			else if ($obj instanceof MonthDelta)
				return $obj->getMonths() != 0;
			else if (\com\livinglogic\ul4on\Utils::isList($obj) || \com\livinglogic\ul4on\Utils::isDict($obj))
				return count($obj) > 0;

			return true;
		}
		throw new ArgumentCountMismatchException("function", "bool", func_num_args(), 0);
	}
}

?>
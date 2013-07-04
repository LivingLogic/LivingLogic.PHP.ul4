<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRandChoice implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "randchoice", count($args), 1);
	}

	public function getName()
	{
		return "randrange";
	}

	public static function _call($obj)
	{
		if (is_string($obj))
		{
			$index = intval(strlen($obj) * FunctionRandom::call());
			return substr($obj, $index, 1);
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$index = intval(count($obj) * FunctionRandom::call());
			return $obj[$index];
		}
		else if ($obj instanceof Color)
		{
			$index = intval(4 * FunctionRandom::call());
			switch ($index)
			{
				case 0:
					return $obj->getR();
				case 1:
					return $obj->getG();
				case 2:
					return $obj->getB();
				case 3:
					return $obj->getA();
				default:
					return 0; // can't happen
			}
		}
		throw new ArgumentTypeMismatchException("randchoice({})", $obj);
	}
}

?>
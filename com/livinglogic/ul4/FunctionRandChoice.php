<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRandChoice extends _Function
{
	public function nameUL4()
	{
		return "randrange";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"sequence", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
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
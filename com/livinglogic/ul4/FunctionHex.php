<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionHex extends _Function
{
	public function nameUL4()
	{
		return "hex";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("number", Signature::$required)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		if (is_int($obj) || is_long($obj))
		{
			if ($obj < 0)
				return "-0x" . dechex(-$obj);
			else
				return "0x" . dechex($obj);
		}
		else if (is_bool($obj))
		{
			return $obj ? "0x1" : "0x0";
		}

		throw new \Exception("hex(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>
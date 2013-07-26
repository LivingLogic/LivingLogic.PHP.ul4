<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionBin extends _Function
{
	public function nameUL4()
	{
		return "bin";
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
				return "-0b" . decbin(-$obj);
			else
				return "0b" . decbin($obj);
		}
		else if (is_bool($obj))
		{
			return $obj ? "0b1" : "0b0";
		}

		throw new \Exception("bin(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>
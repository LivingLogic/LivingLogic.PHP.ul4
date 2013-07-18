<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFromJSON extends _Function
{
	public function nameUL4()
	{
		return "fromjson";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"string", Signature::$required
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
			$result = json_decode($obj, true);
			return $result;
		}
		throw new ArgumentTypeMismatchException("fromjson({})", $obj);
	}
}

?>
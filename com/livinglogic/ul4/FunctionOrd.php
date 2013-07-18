<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionOrd extends _Function
{
	public function nameUL4()
	{
		return "ord";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"c", Signature::$required
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
			$k = mb_convert_encoding($obj, 'UCS-2LE', 'UTF-8');
			if (1 != mb_strlen($k, 'UCS-2LE'))
				throw new \Exception("String " . $obj . " contains more than one unicode character!");
			$k1 = ord(substr($k, 0, 1));
			$k2 = ord(substr($k, 1, 1));
			return $k2 * 256 + $k1;
		}
		throw new \Exception("ord(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>
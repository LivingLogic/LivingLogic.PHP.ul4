<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionChr extends _Function
{
	public function nameUL4()
	{
		return "chr";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"i", Signature::$required
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
			$charValue = mb_convert_encoding(pack('n', $obj), 'UTF-8', 'UTF-16BE');
			if ($obj != FunctionOrd::call($charValue))
			{
				throw new \Exception("Code point " . $obj . " is invalid!");
			}
			return $charValue;
		}
		else if (is_bool($obj))
		{
			return pack("c", $obj ? 0x01 : 0x00);
		}

		throw new \Exception("chr(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>
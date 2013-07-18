<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionOct extends _Function
{
	public function nameUL4()
	{
		return "oct";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"number", Signature::$required
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
				return "-0o" . decoct(-$obj);
			else
				return "0o" . decoct($obj);
		}
		else if (is_bool($obj))
		{
			return $obj ? "0o1" : "0o0";
		}

		throw new \Exception("oct(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>
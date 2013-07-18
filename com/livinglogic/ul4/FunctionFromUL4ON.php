<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFromUL4ON extends _Function
{
	public function nameUL4()
	{
		return "fromul4on";
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
			return \com\livinglogic\ul4on\Utils::loads($obj);

		throw new ArgumentTypeMismatchException("fromul4on({})", $obj);
	}
}

?>
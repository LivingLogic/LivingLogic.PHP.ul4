<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionURLQuote extends _Function
{
	public function nameUL4()
	{
		return "urlquote";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("string", Signature::$required)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		if (is_string($obj))
			return urlencode($obj);

		throw new ArgumentTypeMismatchException("urlquote({})", $obj);
	}
}

?>
<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsList extends _Function
{
	public function nameUL4()
	{
		return "islist";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"obj", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		return !is_null($obj) && \com\livinglogic\ul4on\Utils::isList($obj);
	}
}

?>
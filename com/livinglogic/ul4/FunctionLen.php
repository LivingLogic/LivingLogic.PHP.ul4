<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionLen extends _Function
{
	/*
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::len($args[0]);
		throw new ArgumentCountMismatchException("function", "len", count($args), 1);
	}
	*/

	public function nameUL4()
	{
		return "len";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("sequence", Signature::$required)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		if (is_string($obj))
			return mb_strlen($obj, \com\livinglogic\ul4on\Utils::$encoding);
		else if (obj instanceof UL4Len)
			return $obj->lenUL4();
		else if ($obj instanceof UL4Attributes)
			return count($obj->getAttributeNamesUL4());
		else if (is_array($obj))
			return count($obj);

		throw new \Exception("len(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>
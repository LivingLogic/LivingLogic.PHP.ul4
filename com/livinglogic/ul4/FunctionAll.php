<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAll extends _Function
{
	public function nameUL4()
	{
		return "all";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("iterable", Signature::$required)
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
			for ($i = 0; $i < mb_strlen($obj, \com\livinglogic\ul4on\Utils::$encoding); ++$i)
			{
				if ($obj[$i] == '\0')
					return false;
			}
			return true;
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			for ($i = 0; $i < count($obj); ++$i)
			{
				if (!FunctionBool::call($obj[$i]))
					return false;
			}
			return true;
		}
		else if ($obj instanceof \Iterator)
		{
			for (; $obj->valid(); $obj->next())
			{
				if (!FunctionBool::call($obj->current()))
					return false;
			}
			return true;
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$ao = new \ArrayObject(array_keys($obj));
			return self::call($ao->getIterator());
		}
		throw new ArgumentTypeMismatchException("all({})", obj);
	}
}

?>
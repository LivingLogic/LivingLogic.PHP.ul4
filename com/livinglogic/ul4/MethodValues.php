<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodValues implements Method
{
	public function getName()
	{
		return "values";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "values", args.length, 0);
		}
	}

	public function call($obj)
	{
		if (!\com\livinglogic\ul4on\Utils::isDict($obj))
			throw new ArgumentTypeMismatchException("{}.values()", $obj);

		return new \ArrayIterator($obj);
	}
}

?>
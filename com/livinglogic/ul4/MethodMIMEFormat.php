<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodMIMEFormat implements Method
{
	public function getName()
	{
		return "mimeformat";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "mimeformat", count($args), 0);
		}
	}

	private static $mimeDateFormat = "D, d m Y H:i:s 'T'";

	public static function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new ArgumentTypeMismatchException("{}.mimeformat()", $obj);

		return gmdate(self::$mimeDateFormat, $obj->getTimestamp());
	}
}

?>
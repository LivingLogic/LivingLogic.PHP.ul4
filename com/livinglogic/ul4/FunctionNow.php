<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionNow extends _Function
{
	public function nameUL4()
	{
		return "now";
	}

	public function evaluate($args)
	{
		return self::call();
	}

	public static function call()
	{
		return new \Datetime();
	}

}

?>
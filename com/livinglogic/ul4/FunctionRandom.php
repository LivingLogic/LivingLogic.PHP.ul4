<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRandom extends _Function
{

	public function nameUL4()
	{
		return "random";
	}

	public function evaluate($args)
	{
		return self::call();
	}

	public static function call()
	{
		return rand()/(getrandmax() + 1);
	}

}

?>
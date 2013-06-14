<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionUTCNow extends _Function
{
	public function nameUL4()
	{
		return "utcnow";
	}

	public function evaluate($args)
	{
		return self::call();
	}

	public static function call()
	{
		$utcTimeZone = new \DateTimeZone("UTC");
		$dateTime = new \DateTime("now", $utcTimeZone);

		return $dateTime;
	}
}

?>
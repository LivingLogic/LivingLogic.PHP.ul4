<?php

namespace com\livinglogic\ul4on;

class Utils
{
	public static function makeDate($year, $month, $day, $hour=0, $minute=0, $second=0, $microsecond=0)
	{
		$dt = new \DateTime();
		$dt->setDate($year, $month, $day);
		$dt->setTime($hour, $minute, $second);
		
		return $dt;
	}
	
	public static function isList($obj)
	{
		if (! is_array($obj))
			return false;

		$keys = array_keys($obj);
		for ($i = 0; $i < count($keys); $i++)
		{
			$key = $keys[$i];
			if (! is_int($key))
				return false;
		}

		return true;
	}

	public static function isDict($obj)
	{
		if (! is_array($obj))
			return false;

		$keys = array_keys($obj);
		for ($i = 0; $i < count($keys); $i++)
		{
			$key = $keys[$i];
			if (! is_string($key))
				return false;
		}

		return true;
	}
	
}
?>
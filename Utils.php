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
	
	public static function isArray($array)
	{
		$keys = array_keys($array);
		for ($i = 0; $i < count($keys); $i++)
		{
			$key = $keys[$i];
			if (! is_int($key))
				return false;
		}
	
		return true;
	}
	
}
?>
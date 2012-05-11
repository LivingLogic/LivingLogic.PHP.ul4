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
}
?>
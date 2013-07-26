<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionStr extends _Function
{
	public function nameUL4()
	{
		return "str";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				array("obj", "")
		);
	}

	public function evaluate($args)
	{
		return self::call(count($args) > 0 ? $args[0] : null);
	}

// 	public static SimpleDateFormat strDateFormatter = new SimpleDateFormat("yyyy-MM-dd");
// 	private static SimpleDateFormat strDateTimeFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
// 	private static SimpleDateFormat strTimestampMicroFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss.SSS'000'");

	public static function call($obj)
	{
		if (is_null($obj))
			return "";
		else if ($obj instanceof Undefined)
			return "";
		else if (is_bool($obj))
			return $obj ? "True" : "False";
		else if (is_int($obj) || is_long($obj))
			return "$obj";
		else if (is_float($obj) || is_double($obj))
		{
			$sobj = "$obj";
			$pos = strpos($sobj, "E");
			if (!is_bool($pos))
			{
				return strtolower(str_replace(".0E", "E", $sobj));
			}
			else
			{
				$pos = strpos($sobj, ".");
				if (!is_bool($pos))
				{
					return $sobj;
				}
				else
				{
					return $sobj . ".0";
				}
			}
		}
		else if (is_string($obj))
			return $obj;
		else if ($obj instanceof \DateTime)
		{
			return date_format($obj, "Y-m-d H:i:s");
			// FIXME
// 			if (microsecond(obj) != 0)
// 				return strTimestampMicroFormatter.format(obj);
// 			else
// 			{
// 				if (hour(obj) != 0 || minute(obj) != 0 || second(obj) != 0)
// 					return strDateTimeFormatter.format(obj);
// 				else
// 					return isoDateFormatter.format(obj);
// 			}
		}
		else if ($obj instanceof Color)
			return $obj->__toString();
		else if ($obj instanceof TimeDelta)
			return $obj->toString();
		else if ($obj instanceof MonthDelta)
			return $obj->toString();
		else
			return FunctionRepr::call($obj);
	}

}

?>
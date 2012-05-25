<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

use com\livinglogic\ul4\Color as Color;

class Repr
{
	private $visited = array();

	public function toString($obj)
	{
		if (is_null($obj))
			return "None";
		else if (is_bool($obj))
			return $obj ? "True" : "False";
		else if (is_int($obj) || is_long($obj) || is_double($obj) || is_float($obj))
			return "" . $obj;
		else if (is_string($obj))
			return '"' . addslashes($obj)	. '"';
		else if ($obj instanceof \DateTime)
		{
			return date_format($obj, "YmdHis") . "000000";
		}
		else if ($obj instanceof Color)
			return $obj->repr();
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			if ($this->seen($obj))
				return "[...]";
			array_push($this->visited, $obj);

			try
			{
				$sb = "[";
				$first = true;
				foreach ($obj as $o)
				{
					if ($first)
						$first = false;
					else
						$sb .= ", ";
					$sb .= $this->toString($o);
				}
				$sb .= "]";

				return $sb;
			}
			catch (Exception $e)
			{
				array_pop($this->visited);
				return "{?}";
			}
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			if ($this->seen($obj))
				return "{...}";
			array_push($this->visited, $obj);

			try
			{
				$sb = "{";
				$first = true;

				foreach ($obj as $key => $value)
				{
					if ($first)
						$first = false;
					else
						$sb .= ", ";
					$sb .= $this->toString($key);
					$sb .= ": ";
					$sb .= $this->toString($value);
				}
				$sb .= "}";
				return $sb;
			}
			catch (Exception $e)
			{
				array_pop($this->visited);
				return "{?}";
			}
		}
		return "?";
	}

	private function seen($obj)
	{
		foreach ($this->visited as $item)
		{
			if ($obj === $item)
				return true;
		}
		return false;
	}
}

class Utils
{
	public static function repr($obj)
	{
		$r = new Repr();
		return $r->toString($obj);
	}

	public static function type($obj)
	{
		if (is_null($obj))
			return "none";
		else if (is_bool($obj))
			return 'bool';
		else if (is_int($obj))
			return 'int';
		else if (is_float($obj) || is_double($obj))
			return 'float';
		else if ($obj instanceof \com\livinglogic\ul4\Color)
			return 'color';
		else if ($obj instanceof InterpretedTemplate)
			return 'template';
		else if ($obj instanceof \DateTime)
			return 'date';
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
			return "dict";
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
			return "list";
		else
			return null;
	}

	public static function getItem($container, $key)
	{
		if (\com\livinglogic\ul4on\Utils::isDict($container))
		{
			if (array_key_exists($key, $container))
				return $container[$key];
			else
				throw new Exception("Key " . self::repr($key) . " not found");
		}
		else if (\com\livinglogic\ul4on\Utils::isList($container))
		{
			if (is_int($key))
			{
				$orgkey = $key;
				if ($key < 0)
					$key += count($container);
				if ($key < 0 || $key >= count($container))
					throw new Exception("Index $orgkey is out of bounds!");
				return $container[$key];
			}
			else
				throw new Exception("list[" . self::type($key) . "] not supported!");
		}
		else if (is_string($container))
		{
			if (is_int($key))
			{
				$orgkey = $key;
				if ($key < 0)
					$key += strlen($container);
				if ($key < 0 || $key >= strlen($container))
					throw new Exception("Index $orgkey is out of bounds!");
				return $container[$key];
			}
			else
				throw new Exception("string[" . self::type($key) . "] not supported!");
		}
		else if ($container instanceof Color)
		{
			if (is_int($key))
			{
				if ($key < 0 || $key > 3)
					throw new Exception("Index $key is not in [0..3]!");

				return $container->get($key);
			}
			else
				throw new Exception("color[" . self::type($key) . "] not supported!");
		}
	}

	public static function str($obj)
	{
		if (is_null($obj))
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
			return date_format($obj, "YmdHis") . "000000";
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
		else
			return self::repr($obj);

	}

	public static function xmlescape($obj)
	{
		if (is_null($obj))
			return "";

		$str = self::str($obj);
		$length = strlen($str);
		$sb = "";

		$search = array( "<"   , ">"   , "&"    , "'"    , '"'     );
		$replace = array("&lt;", "&gt;", "&amp;", "&#39;", '&quot;');
		$str = str_replace($search, $replace, $str);

		$buffer = "";
		for ($i = 0; $i < 32; $i++)
		{
			$c = chr($i);
			if ($c != '\t' && $c != '\n' && $c != '\r')
				$str = str_replace($c, "&#$i;", $str);
		}
		for ($i = 128; $i < 160; $i++)
			$str = str_replace(chr($i), "&#$i;", $str);
	}

	public static function add($obj1, $obj2)
	{
		if (is_string($obj1) && is_string($obj2))
			return $obj1 . $obj2;
		else if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return $obj1 + $obj2;

		throw new \Exception(self::objectType($obj1) . " + " . self::objectType($obj2) . " not supported");
	}

	public static function sub($obj1, $obj2)
	{
		if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return $obj1 - $obj2;

		throw new \Exception(self::objectType($obj1) + " + " + self::objectType($obj2) . " not supported");
	}

	private static function mulArray($array, $count)
	{
		$result = array();

		for ($i = 0; $i < $count; $i++)
			$result = array_merge($result, $array);

		return $result;
	}

	public static function mul($obj1, $obj2)
	{
		if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return $obj1 * $obj2;

		if (is_string($obj1) && (is_int($obj2) || is_long($obj2) || is_bool($obj2)))
			return str_repeat($obj1, $obj2);
		if (is_string($obj2) && (is_int($obj1) || is_long($obj1) || is_bool($obj1)))
			return str_repeat($obj2, $obj1);

		if (is_array($obj1) && (is_int($obj2) || is_long($obj2) || is_bool($obj2)))
			return self::mulArray($obj1, $obj2);
		if (is_array($obj2) && (is_int($obj1) || is_long($obj1) || is_bool($obj1)))
			return self::mulArray($obj2, $obj1);

		throw new \Exception(self::objectType($obj1) + " + " + self::objectType($obj2) . " not supported");
	}

	public static function objectType($obj)
	{
		if (is_array($obj))
			return "array";
		else if (is_bool($obj))
			return "bool";
		else if (is_int($obj))
			return "int";
		else if (is_long($obj))
			return "long";
		else if (is_float($obj))
			return "float";
		else if (is_double($obj))
			return "double";

		if (!is_null($obj) && !is_object($obj))
			throw new \Exception("unknown object type");

		return (!is_null($obj)) ? get_class($obj) : "null";
	}

	public static function getBool($obj)
	{
		if (is_null($obj))
			return false;
		else if (is_bool($obj))
			return $obj;
		else if (is_string($obj))
			return strlen($obj) > 0;
		else if (is_int($obj) || is_long($obj))
			return $obj != 0;
		else if (is_float($obj) || is_double($obj))
			return $obj != 0.;
		else if ($obj instanceof \DateTime)
			return True;
		else if (\com\livinglogic\ul4on\Utils::isList($obj) || \com\livinglogic\ul4on\Utils::isDict($obj))
			return count($obj) > 0;

		return true;
	}

	public static function contains($obj, $container)
	{
		if (is_string($container))
		{
			if (is_string($obj))
			{
				if (is_bool(strpos($container, $obj)))
					return False;
				else
					return True;
			}
		}
		else if (\com\livinglogic\ul4on\Utils::isList($container))
			return in_array($obj, $container, True);
		else if (\com\livinglogic\ul4on\Utils::isDict($container))
			return array_key_exists($obj, $container);

		throw new \Exception(self::objectType($obj) . " in " . self::objectType($container) . " not supported!");
	}

	public static function cmp($obj1, $obj2, $op)
	{
		if (is_string($obj1) && is_string($obj2))
			return ($obj1 > $obj2) - ($obj1 < $obj2);
		else if ((is_bool($obj1) || is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1)) &&
				(is_bool($obj2) || is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2)))
		{
			if (is_bool($obj1))
				$obj1 = $obj1 ? 1 : 0;
			if (is_bool($obj2))
				$obj2 = $obj2 ? 1 : 0;
			return ($obj1 > $obj2) - ($obj1 < $obj2);
		}

		throw new \Exception(self::objectType($obj1) . " " . $op . " " . self::objectType($obj2) . " not supported!");
	}

	public static function eq($obj1, $obj2)
	{
		if (!is_null($obj1) && !is_null($obj2))
			return self::cmp($obj1, $obj2, "==") == 0;
		return is_null($obj1) == is_null($obj2);
	}

	public static function ge($obj1, $obj2)
	{
		if (!is_null($obj1) && !is_null($obj2))
			return self::cmp($obj1, $obj2, ">=") >= 0;
		if (is_null($obj1) != is_null($obj2))
			throw new \Exception(self::objectType($obj1) . " >= " . self::objectType($obj2) . " not supported!");
		return true;
	}

	/*
	public static function getItem($arg1, $arg2)
	{
		if (\com\livinglogic\ul4on\Utils::isDict($arg1))
		{
			if (!is_string($arg2))
				throw new \Exception(self::objectType($arg1) . "[" . self::objectType($arg2) . "] not supported!");

			if (!array_key_exists($arg2, arg1))
				throw new \Exception("key $arg2 not found");
			else
				return $arg1[$arg2];
		}
		else if (is_int($arg2) || is_long($arg2))
		{
			if (is_string($arg1))
			{
				if ($arg2 < 0)
					$arg2 += strlen($arg1);
				return $arg1->{$arg2};
			}
			else if (\com\livinglogic\ul4on\Utils::isDict($arg1))
			{
				if ($arg2 < 0)
					$arg2 += count($arg1);
				return $arg1[$arg2];
			}
			else if ($arg1 instanceof Color)
				return $arg1->get($arg2);
		}

		throw new \Exception(self::objectType($arg1) . "[" . self::objectType($arg2) . "] not supported!");
	}
	*/

	public static function floordiv($obj1, $obj2)
	{
		if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return floor($obj1 / $obj2);

		throw new \Exception(self::objectType($obj1) . " // " . self::objectType($obj2) . " not supported!");
	}

	public static function gt($obj1, $obj2)
	{
		if (!is_null($obj1) && !is_null($obj2))
			return self::cmp($obj1, $obj2, ">") > 0;
		if (is_null($obj1) != is_null($obj2))
			throw new \Exception(self::objectType($obj1) . " > " . self::objectType($obj2) . " not supported!");
		return false;
	}

	public static function le($obj1, $obj2)
	{
		if (!is_null($obj1) && !is_null($obj2))
			return self::cmp($obj1, $obj2, "<=") <= 0;
		if (is_null($obj1) != is_null($obj2))
			throw new \Exception(self::objectType($obj1) . " <= " . self::objectType($obj2) . " not supported!");
		return true;
	}

	public static function lt($obj1, $obj2)
	{
		if (!is_null($obj1) && !is_null($obj2))
			return self::cmp($obj1, $obj2, "<") < 0;
		if (is_null($obj1) != is_null($obj2))
			throw new \Exception(self::objectType($obj1) . " < " . self::objectType($obj2) . " not supported!");
		return false;
	}

	public static function mod($obj1, $obj2)
	{
		if ((is_int($obj1) || is_long($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_bool($obj2)))
		{
			$div = self::floordiv($obj1, $obj2);
			$mod = $obj1 - $div * $obj2;

			if ($mod != 0 && (($obj2 < 0 && $mod > 0) || ($obj2 > 0 && $mod < 0)))
			{
				$mod += $obj2;
				--$div;
			}
			return $obj1 - $div * $obj2;
		}
		else if ((is_float($obj1) || is_double($obj1)) && (is_float($obj2) || is_double($obj2)))
		{
			$div = floor($obj1 / $obj2);
			$mod = $obj1 - $div * $obj2;

			if ($mod != 0 && (($obj2 < 0 && $mod > 0) || ($obj2 > 0 && $mod < 0)))
			{
				$mod += $obj2;
				--$div;
			}
			return $obj1 - $div * $obj2;
		}
		else if ($obj1 instanceof Color && $obj2 instanceof Color)
		{
			return $obj1->blend($obj2);
		}

		throw new \Exception(self::objectType($obj1) . " % " . self::objectType($obj2) . " not supported!");
	}

	public static function ne($obj1, $obj2)
	{
		if (!is_null($obj1) && !is_null($obj2))
			return self::cmp($obj1, $obj2, "!=") != 0;
		return (is_null($obj1) != is_null(obj2));
	}

	public static function notcontains($obj, $container)
	{
		return !self::contains($obj, $container);
	}

}

?>
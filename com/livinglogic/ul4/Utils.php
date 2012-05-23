<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

use com\livinglogic\ul4\Color as Color;

class Repr
{
	private $visited = array();

	public function toString($obj)
	{
		if ($obj == null)
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
		else if (com\livinglogic\ul4on\Utils::isList($obj))
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
		else if (com\livinglogic\ul4on\Utils::isDict($obj))
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
}

?>
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
		else if ($obj instanceof Undefined)
			return "Undefined";
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
		/*
		else if ($obj instanceof \Iterator)
		{
			$first = true;
			$sb = "iterator[";
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
		*/
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

class StringIterator implements \Iterator
{
	var $string;
	var $stringSize;
	var $index;

	public function __construct($string)
	{
		$this->string = $string;
		if (is_null($string))
			$this->stringSize = -1;
		else
			$this->stringSize = strlen($string);
		$this->index = 0;
	}

	public function rewind()
	{
		$this->index = 0;
	}

	public function current()
	{
		return $this->string[$this->index];
	}

	public function key()
	{
		return $this->index;
	}

	public function next()
	{
		++$this->index;
	}

	public function valid()
	{
		return (!is_null($this->string) && $this->index < $this->stringSize);
	}
}

class SequenceEnum implements \Iterator
{
	var $sequenceIterator;
	var $index;
	var $start;

	public function __construct($sequenceIterator, $start)
	{
		$this->sequenceIterator = $sequenceIterator;
		$this->index = $start;
		$this->start = $start;
	}

	public function rewind()
	{
		$this->sequenceIterator->rewind();
		$this->index = $this->start;
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->index);
		array_push($retVal, $this->sequenceIterator->current());
		return $retVal;
	}

	public function key()
	{
		return $this->sequenceIterator->key();
	}

	public function next()
	{
		$this->sequenceIterator->next();
		$this->index++;
	}

	public function valid()
	{
		return $this->sequenceIterator->valid();
	}
}

class SequenceEnumFL implements \Iterator
{
	var $sequenceIterator;

	var $index;
	var $start;
	var $current;
	var $key;
	var $valid;
	var $internalNextCalled;

	public function __construct($sequenceIterator, $start)
	{
		$this->sequenceIterator = $sequenceIterator;
		$this->index = $start;
		$this->start = $start;
		$this->valid = $this->sequenceIterator->valid();
		$this->internalNextCalled = false;

		if ($this->valid)
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
	}

	public function rewind()
	{
		// unused
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->index);
		array_push($retVal, $this->index === $this->start);

		if (!$this->internalNextCalled)
		{
			$this->sequenceIterator->next();
			$this->internalNextCalled = true;
		}

		array_push($retVal, !$this->sequenceIterator->valid());

		array_push($retVal, $this->current);
		return $retVal;
	}

	public function key()
	{
		return $this->key;
	}

	public function next()
	{
		if (!$this->internalNextCalled)
			$this->sequenceIterator->next();

		$this->internalNextCalled = false;

		if ($this->sequenceIterator->valid())
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
		$this->valid = $this->sequenceIterator->valid();

		$this->index++;
	}

	public function valid()
	{
		return $this->valid;
	}
}

class SequenceIsFirstLast implements \Iterator
{
	var $sequenceIterator;

	var $first = true;
	var $current;
	var $key;
	var $valid;
	var $internalNextCalled;

	public function __construct($sequenceIterator)
	{
		$this->sequenceIterator = $sequenceIterator;
		$this->valid = $this->sequenceIterator->valid();
		$this->internalNextCalled = false;

		if ($this->valid)
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
	}

	public function rewind()
	{
		// unused
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->first);

		if (!$this->internalNextCalled)
		{
			$this->sequenceIterator->next();
			$this->internalNextCalled = true;
		}
		array_push($retVal, !$this->sequenceIterator->valid());

		array_push($retVal, $this->current);
		return $retVal;
	}

	public function key()
	{
		return $this->key;
	}

	public function next()
	{
		if (!$this->internalNextCalled)
			$this->sequenceIterator->next();

		$this->first = false;
		$this->internalNextCalled = false;

		if ($this->sequenceIterator->valid())
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
		$this->valid = $this->sequenceIterator->valid();
	}

	public function valid()
	{
		return $this->valid;
	}
}

class SequenceIsFirst implements \Iterator
{
	var $sequenceIterator;

	var $first = true;

	public function __construct($sequenceIterator)
	{
		$this->sequenceIterator = $sequenceIterator;
	}

	public function rewind()
	{
		// unused
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->first);
		array_push($retVal, $this->sequenceIterator->current());
		return $retVal;
	}

	public function key()
	{
		return $this->sequenceIterator->key();
	}

	public function next()
	{
		$this->sequenceIterator->next();
		$this->first = false;
	}

	public function valid()
	{
		return $this->sequenceIterator->valid();
	}
}

class SequenceIsLast implements \Iterator
{
	var $sequenceIterator;

	var $current;
	var $key;
	var $valid;
	var $internalNextCalled;

	public function __construct($sequenceIterator)
	{
		$this->sequenceIterator = $sequenceIterator;
		$this->valid = $this->sequenceIterator->valid();
		$this->internalNextCalled = false;

		if ($this->valid)
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
	}

	public function rewind()
	{
		// unused
	}

	public function current()
	{
		$retVal = array();

		if (!$this->internalNextCalled)
		{
			$this->sequenceIterator->next();
			$this->internalNextCalled = true;
		}
		array_push($retVal, !$this->sequenceIterator->valid());

		array_push($retVal, $this->current);
		return $retVal;
	}

	public function key()
	{
		return $this->key;
	}

	public function next()
	{
		if (!$this->internalNextCalled)
			$this->sequenceIterator->next();

		$this->internalNextCalled = false;

		if ($this->sequenceIterator->valid())
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
		$this->valid = $this->sequenceIterator->valid();
	}

	public function valid()
	{
		return $this->valid;
	}
}

class StringReversedIterator implements \Iterator
{
	var $string;
	var $stringSize;
	var $index;

	public function __construct($string)
	{
		$this->string = $string;
		$this->stringSize = strlen($string);
		$this->index = $this->stringSize - 1;
	}

	public function current()
	{
		if (!$this->valid())
			throw new \Exception("No more characters available!");

		return $this->string{$this->index};
	}

	public function key()
	{
		return $this->index;
	}

	public function next()
	{
		$this->index--;
	}

	public function rewind()
	{
		$this->index = $this->stringSize - 1;
	}

	public function valid()
	{
		return $this->index >= 0;
	}
}

class ListReversedIterator implements \Iterator
{
	var $list;
	var $listSize;
	var $index;

	public function __construct($list)
	{
		$this->list = $list;
		$this->listSize = count($list);
		$this->index = $this->listSize - 1;
	}

	public function current()
	{
		if (!$this->valid())
			throw new \Exception("No more items available!");

		return $this->list[$this->index];
	}

	public function key()
	{
		return $this->index;
	}

	public function next()
	{
		$this->index--;
	}

	public function rewind()
	{
		$this->index = $this->listSize - 1;
	}

	public function valid()
	{
		return $this->index >= 0;
	}
}

class MapItemIterator implements \Iterator
{
	private $iterator;

	public function __construct($map)
	{
		$this->iterator = new \ArrayIterator($map);
	}

	public function current()
	{
		return array($this->iterator->key(), $this->iterator->current());
	}

	public function key()
	{
		return $this->iterator->key();
	}

	public function next()
	{
		$this->iterator->next();
	}

	public function rewind()
	{
		$this->iterator->rewind();
	}

	public function valid()
	{
		return $this->iterator->valid();
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
				return new UndefinedKey($key);
		}
		else if (\com\livinglogic\ul4on\Utils::isList($container))
		{
			if (is_int($key))
			{
				$orgkey = $key;
				if ($key < 0)
					$key += count($container);
				if ($key < 0 || $key >= count($container))
					return new UndefinedIndex($key);
				return $container[$key];
			}
			else
				throw new\Exception("list[" . self::type($key) . "] not supported!");
		}
		else if (is_string($container))
		{
			if (is_int($key))
			{
				$orgkey = $key;
				if ($key < 0)
					$key += strlen($container);
				if ($key < 0 || $key >= strlen($container))
					return new UndefinedIndex($key);
				return $container[$key];
			}
			else
				throw new\Exception("string[" . self::type($key) . "] not supported!");
		}
		else if ($container instanceof Color)
		{
			if (is_int($key))
			{
				if ($key < 0 || $key > 3)
					return new UndefinedIndex($key);

				return $container->get($key);
			}
			else
				throw new\Exception("color[" . self::type($key) . "] not supported!");
		}
	}

	public static function str($obj)
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

		$search = array( "&");
		$replace = array("&amp;");
		$str = str_replace($search, $replace, $str);

		$search = array( "<"   , ">"   , "'"    , '"'     );
		$replace = array("&lt;", "&gt;", "&#39;", '&quot;');
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

		return $str;
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
		else if (is_string($obj))
			return "string";

		if (!is_null($obj) && !is_object($obj))
			throw new \Exception("unknown object type");

		return (!is_null($obj)) ? get_class($obj) : "null";
	}

	public static function getBool($obj)
	{
		if (is_null($obj))
			return false;
		else if ($obj instanceof Undefined)
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
		else if ($obj instanceof TimeDelta)
			return $obj->getDays() != 0 || $obj->getSeconds() != 0 || $obj->getMicroseconds() != 0;
		else if ($obj instanceof MonthDelta)
			return $obj->getMonths() != 0;
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

	public static function truediv($obj1, $obj2)
	{
		if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return $obj1 / $obj2;

		throw new \Exception(self::objectType($obj1) . " / " . self::objectType($obj2) . " not supported!");
	}

	public static function neg($obj)
	{
		if (is_int($obj) || is_long($obj) || is_float($obj) || is_double($obj))
			return -$obj;
		else if (is_bool($obj))
			return $obj ? -1 : 0;

		throw new \Exception("-" . self::objectType($obj) . " not supported!");
	}

	public static function utcnow()
	{
		$utcTimeZone = new \DateTimeZone("GMT");
		$dateTime = new \DateTime("now", $utcTimeZone);

		return $dateTime;
	}

	public static function random()
	{
		return rand()/(getrandmax() + 1);
	}

	public static function csv($obj)
	{
		if (is_null($obj))
			return "";
		if (! is_string($obj))
			$obj = self::repr($obj);

		$pos0 = strpos($obj, '"');
		$pos1 = strpos($obj, ',');
		$pos2 = strpos($obj, '\n');

		if (is_bool($pos0) && is_bool($pos1) && is_bool($pos2))
			return $obj;

		$obj = str_replace('"', '""', $obj);
		return '"' . $obj . '"';
	}

	public static function toInteger($obj)
	{
		if (func_num_args() == 1)
		{
			if (is_string($obj))
				return intval($obj);
			else if (is_int($obj) || is_long($obj))
				return $obj;
			else if (is_bool($obj))
				return $obj ? 1 : 0;
			else if (is_float($obj) || is_double($obj))
				return intval($obj);

			throw new \Exception("int(" . self::objectType($obj) . ") not supported!");
		}
		else if (func_num_args() == 2)
		{
			$obj2 = func_get_arg(1);
			if (is_string($obj))
			{
				if (is_int($obj2) || is_long($obj2))
					return intval($obj, $obj2);
			}
			throw new \Exception("int(" . self::objectType($obj) . ", " . self::objectType($obj2) . ") not supported!");
		}
	}

	public static function toFloat($obj)
	{
		if (is_string($obj))
			return doubleval($obj);
		else if (is_int($obj) || is_long($obj))
			return doubleval($obj);
		else if (is_bool($obj))
			return $obj ? 1.0 : 0.0;
		else if (is_float($obj) || is_double($obj))
			return $obj;
		throw new \Exception("float(" . self::objectType($obj) . ") not supported!");
	}

	public static function len($obj)
	{
		if (is_string($obj))
			return strlen($obj);
		else if (is_array($obj))
			return count($obj);
		throw new \Exception("len(" . self::objectType($obj) . ") not supported!");
	}

	public static function iterator($obj)
	{
		if (is_string($obj))
			return new StringIterator($obj);
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$ao = new \ArrayObject(array_keys($obj));
			return $ao->getIterator();
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$ao = new \ArrayObject($obj);
			return $ao->getIterator();
		}
		else if ($obj instanceof \Iterator)
			return $obj;
		throw new \Exception("iter(" . self::objectType($obj) . ") not supported!");
	}

	public static function enumerate($obj)
	{
		if (func_num_args() == 2)
			$start = func_get_arg(1);
		else if (func_num_args() == 1)
			$start = 0;

		return new SequenceEnum(self::iterator($obj), self::_toInt($start));
	}

	private static function _toInt($arg)
	{
		if (is_bool($arg))
			return $arg ? 1 : 0;
		else if (is_int($arg) || is_long($arg) || is_float($arg) || is_double($arg))
			return intval($arg);
		throw new \Exception("can't convert " . self::objectType($arg) . " to int!");
	}

	public static function enumfl($obj, $start=0)
	{
		return new SequenceEnumFL(self::iterator($obj), self::_toInt($start));
	}

	public static function isfirstlast($obj)
	{
		return new SequenceIsFirstLast(self::iterator($obj));
	}

	public static function isfirst($obj)
	{
		return new SequenceIsFirst(self::iterator($obj));
	}

	public static function islast($obj)
	{
		return new SequenceIsLast(self::iterator($obj));
	}

	public static function unichr($intval)
	{
		return mb_convert_encoding(pack('n', $intval), 'UTF-8', 'UTF-16BE');
	}

	public static function uniord($u)
	{
		$k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
		$k1 = ord(substr($k, 0, 1));
		$k2 = ord(substr($k, 1, 1));
		return $k2 * 256 + $k1;
	}

	public static function chr($obj)
	{
		if (is_int($obj) || is_long($obj))
		{
			$charValue = self::unichr($obj);
			if ($obj != self::uniord($charValue))
			{
				throw new \Exception("Code point " . $obj . " is invalid!");
			}
			return $charValue;
		}
		else if (is_bool($obj))
		{
			return $obj ? pack("c", 0x01) : pack("c", 0x00);
		}

		throw new \Exception("chr(" . self::objectType($obj) . ") not supported!");
	}

	public static function ord($obj)
	{
		if (is_string($obj))
		{
			if (1 != mb_strlen($obj)) // TODO: "й" fuehrt zu\Exception
			{
				throw new \Exception("String " . $obj . " contains more than one unicode character!");
			}
			return self::uniord($obj[0]);
		}
		throw new \Exception("ord(" . self::objectType($obj) . ") not supported!");
	}

	public static function hex($obj)
	{
		if (is_int($obj) || is_long($obj))
		{
			if ($obj < 0)
				return "-0x" . dechex(-$obj);
			else
				return "0x" . dechex($obj);
		}
		else if (is_bool($obj))
		{
			return $obj ? "0x1" : "0x0";
		}

		throw new \Exception("hex(" . self::objectType($obj) . ") not supported!");
	}

	public static function oct($obj)
	{
		if (is_int($obj) || is_long($obj))
		{
			if ($obj < 0)
				return "-0o" . decoct(-$obj);
			else
				return "0o" . decoct($obj);
		}
		else if (is_bool($obj))
		{
			return $obj ? "0o1" : "0o0";
		}

		throw new \Exception("oct(" . self::objectType($obj) . ") not supported!");
	}

	public static function bin($obj)
	{
		if (is_int($obj) || is_long($obj))
		{
			if ($obj < 0)
				return "-0b" . decbin(-$obj);
			else
				return "0b" . decbin($obj);
		}
		else if (is_bool($obj))
		{
			return $obj ? "0b1" : "0b0";
		}

		throw new \Exception("bin(" . self::objectType($obj) . ") not supported!");
	}

	public static function abs($arg)
	{
		if (is_int($arg) || is_long($arg) || is_float($arg) || is_double($arg))
		{
			if ($arg >= 0)
				return $arg;
			else
				return -$arg;
		}
		else if (is_bool($arg))
			return $arg ? 1 : 0;

		throw new\Exception("abs(" . self::objectType($arg) . ") not supported!");
	}

	public static function range($obj)
	{
		$start = 0;
		$stop = $obj;
		$step = 1;

		if (func_num_args() == 2)
		{
			$start = $obj;
			$stop  = func_get_arg(1);
		}

		if (func_num_args() == 3)
		{
			$start = $obj;
			$stop  = func_get_arg(1);
			$step  = func_get_arg(2);
		}

		return range(self::_toInt($start), self::_toInt($stop)-1, self::_toInt($step));
	}

	public static function sorted($obj)
	{
		if (is_string($obj))
		{
			$retVal = array();
			$length = strlen($obj);
			for ($i = 0; $i < $length; $i++)
			{
				array_push($retVal, $obj[$i]);
			}
			asort($retVal);
			return $retVal;
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$length = count($obj);
			$retVal = array();
			for ($i = 0; $i < $length; $i++)
			{
				array_push($retVal, $obj[$i]);
			}
			asort($retVal);
			return $retVal;
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$retVal = array_keys($obj);
			asort($retVal);
			return $retVal;
		}
		else if ($obj instanceof \Iterator)
		{
			$retVal = array();
			foreach ($obj as $key => $value)
			{
				array_push($retVal, $value);
			}
			asort($retVal);
			return $retVal;
		}

		throw new \Exception("sorted(" . self::objectType($obj) . ") not supported!");
	}

	public static function getSliceStartPos($sequenceSize, $virtualPos)
	{
		$retVal = $virtualPos;
		if (0 > $retVal)
		{
			$retVal += $sequenceSize;
		}
		if (0 > $retVal)
		{
			$retVal = 0;
		}
		else if ($sequenceSize < $retVal)
		{
			$retVal = $sequenceSize;
		}
		return $retVal;
	}

	public static function getSliceEndPos($sequenceSize, $virtualPos)
	{
		$retVal = $virtualPos;
		if (0 > $retVal)
		{
			$retVal += $sequenceSize;
		}
		if (0 > $retVal)
		{
			$retVal = 0;
		}
		else if ($sequenceSize < $retVal)
		{
			$retVal = $sequenceSize;
		}
		return $retVal;
	}

	public static function unpackVariable(&$variables, $varname, $item)
	{
		if (is_string($varname))
		{
			$variables[$varname] = $item;
		}
		else
		{
			$itemIter = self::iterator($item);
			$varnames = $varname;
			$varnameCount = count($varnames);

			for ($i = 0;;++$i)
			{
				if ($itemIter->valid())
				{
					if ($i < $varnameCount)
					{
						self::unpackVariable($variables, $varnames[$i], $itemIter->current());
						$itemIter->next();
					}
					else
					{
						throw new UnpackingException("mismatched for loop unpacking: " . $varnameCount . " varnames, >" . $i . " items");
					}
				}
				else
				{
					if ($i < $varnameCount)
					{
						throw new UnpackingException("mismatched for loop unpacking: " . $varnameCount . "+ varnames, " . $i . " items");
					}
					else
					{
						break;
					}
				}
			}
		}
	}


	public static function formatVarname(&$buffer, $varname)
	{
		if (is_string($varname))
			$buffer .= $varname;
		else
		{
			$varnames = $varname;
			$buffer .= "(";
			$count = 0;
			foreach ($varnames as $subvarname)
			{
				++$count;
				self::formatVarname($buffer, $subvarname);
				if ($count == 1 || $count != len($varnames))
					$buffer .= ", ";
			}
			$buffer .= ")";
		}
	}

	public static function date($year, $month, $day, $hour=0, $minute=0, $second=0, $microsecond=0)
	{
		$dt = new \DateTime();

		$dt->setDate($year, $month, $day);
		$dt->setTime($hour, $minute, $second);

		return $dt;
	}
}

?>
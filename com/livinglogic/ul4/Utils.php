<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

use com\livinglogic\ul4\Color as Color;

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
		else if (is_string($obj))
			return 'str';
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

	public static function add($obj1, $obj2)
	{
		if (is_string($obj1) && is_string($obj2))
			return $obj1 . $obj2;
		else if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return $obj1 + $obj2;
		else if ($obj1 instanceof \DateTime && ($obj2 instanceof TimeDelta || $obj2 instanceof MonthDelta))
		{
			if ($obj2 instanceof MonthDelta)
			{
				$dateInterval = new \DateInterval("P" . $obj2->getMonths() . "M");
				$obj1->add($dateInterval);
				return $obj1;
			}
			else
			{
				$distr = "P" . $obj2->getDays() . "D";
				if (!is_null($obj2->getSeconds()) && $obj2->getSeconds() != 0)
					$distr .= "T" . $obj2->getSeconds() . "S";
				$dateInterval = new \DateInterval($distr);
				$obj1->add($dateInterval);
				return $obj1;
			}
		}
		else if (($obj1 instanceof TimeDelta || $obj1 instanceof MonthDelta) && $obj2 instanceof \DateTime)
		{
			if ($obj1 instanceof MonthDelta)
			{
				$dateInterval = new \DateInterval("P" . $obj1->getMonths() . "M");
				$obj2->add($dateInterval);
				return $obj2;
			}
			else
			{
				$distr = "P" . $obj1->getDays() . "D";
				if (!is_null($obj1->getSeconds()) && $obj1->getSeconds() != 0)
					$distr .= "T" . $obj1->getSeconds() . "S";
				$dateInterval = new \DateInterval($distr);
				$obj2->add($dateInterval);
				return $obj2;
			}
		}
		else if ($obj1 instanceof TimeDelta && $obj2 instanceof TimeDelta)
		{
			return new TimeDelta($obj1->getDays() + $obj2->getDays(), $obj1->getSeconds() + $obj2->getSeconds(), $obj1->getMicroseconds() + $obj2->getMicroseconds());
		}
		else if ($obj1 instanceof MonthDelta && $obj2 instanceof MonthDelta)
		{
			return new MonthDelta($obj1->getMonths() + $obj2->getMonths());
		}

		throw new \Exception(self::objectType($obj1) . " + " . self::objectType($obj2) . " not supported");
	}

	public static function sub($obj1, $obj2)
	{
		if ((is_int($obj1) || is_long($obj1) || is_float($obj1) || is_double($obj1) || is_bool($obj1)) &&
				(is_int($obj2) || is_long($obj2) || is_float($obj2) || is_double($obj2) || is_bool($obj2)))
			return $obj1 - $obj2;
		else if ($obj1 instanceof \DateTime && $obj2 instanceof TimeDelta)
		{
			$distr = "P" . $obj2->getDays() . "D";
			if (!is_null($obj2->getSeconds()) && $obj2->getSeconds() != 0)
				$distr .= "T" . $obj2->getSeconds() . "S";
			$dateInterval = new \DateInterval($distr);
			$obj1->sub($dateInterval);
			return $obj1;
		}
		else if ($obj1 instanceof \DateTime && $obj2 instanceof MonthDelta)
		{
			$dateInterval = new \DateInterval("P" . $obj2->getMonths() . "M");
			$obj1->sub($dateInterval);
			return $obj1;
		}
		else if ($obj1 instanceof TimeDelta && $obj2 instanceof TimeDelta)
		{
			return new TimeDelta($obj1->getDays() - $obj2->getDays(), $obj1->getSeconds() - $obj2->getSeconds(), $obj1->getMicroseconds() - $obj2->getMicroseconds());
		}
		else if ($obj1 instanceof MonthDelta && $obj2 instanceof MonthDelta)
		{
			return new MonthDelta($obj1->getMonths() - $obj2->getMonths());
		}

		throw new \Exception(self::objectType($obj1) + " - " + self::objectType($obj2) . " not supported");
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
		else if (\com\livinglogic\ul4\FunctionIsColor::_call($container))
			return $container->getR() == $obj || $container->getG() == $obj || $container->getB() == $obj || $container->getA() == $obj;

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
		else if (\com\livinglogic\ul4\FunctionIsTimeDelta::_call($obj1) && \com\livinglogic\ul4\FunctionIsTimeDelta::_call($obj2))
		{
			$temp = Utils::cmp($obj1->getDays(), $obj2->getDays(), "==");
			if ($temp == 0)
				$temp = Utils::cmp($obj1->getSeconds(), $obj2->getSeconds(), "==");
			if ($temp == 0)
				$temp = Utils::cmp($obj1->getMicroseconds(), $obj2->getMicroseconds(), "==");
			return $temp;
		}
		else if (\com\livinglogic\ul4\FunctionIsMonthDelta::_call($obj1) && \com\livinglogic\ul4\FunctionIsMonthDelta::_call($obj2))
		{
			return Utils::cmp($obj1->getMonths(), $obj2->getMonths(), "==");
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

	private static function _toInt($arg)
	{
		if (is_bool($arg))
			return $arg ? 1 : 0;
		else if (is_int($arg) || is_long($arg) || is_float($arg) || is_double($arg))
			return intval($arg);
		throw new \Exception("can't convert " . self::objectType($arg) . " to int!");
	}

	public static function ord($obj)
	{
		if (is_string($obj))
		{
			$k = mb_convert_encoding($obj, 'UCS-2LE', 'UTF-8');
			if (1 != mb_strlen($k, 'UCS-2LE'))
				throw new \Exception("String " . $obj . " contains more than one unicode character!");
			$k1 = ord(substr($k, 0, 1));
			$k2 = ord(substr($k, 1, 1));
			return $k2 * 256 + $k1;
		}
		throw new \Exception("ord(" . self::objectType($obj) . ") not supported!");
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
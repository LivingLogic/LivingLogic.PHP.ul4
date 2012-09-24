<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodFind implements Method
{
	public function getName()
	{
		return "find";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			case 2:
				return self::call($obj, $args[0], $args[1]);
			case 3:
				return self::call($obj, $args[0], $args[1], $args[2]);
			default:
				throw new ArgumentCountMismatchException("method", "find", count($args), 1, 3);
		}
	}

	public static function call($obj, $sub)
	{
		$start = null;
		if (func_num_args() == 3)
			$start = func_get_arg(2);

		$end = null;
		if (func_num_args() == 4)
			$end = func_get_arg(3);

		if ((is_string($obj) && is_string($sub)) || \com\livinglogic\ul4on\Utils::isList($obj))
		{
			if (func_num_args() == 2)
				return self::call2($obj, $sub);
			elseif (func_num_args() == 3)
				return self::call3($obj, $sub, $start);
			elseif (func_num_args() == 4)
				return self::call4($obj, $sub, $start, $end);
		}

		if (func_num_args() == 2)
			throw new ArgumentTypeMismatchException("{}.find({})", $obj, $sub);
		elseif (func_num_args() == 3)
			throw new ArgumentTypeMismatchException("{}.find({})", $obj, $sub, $start);
		elseif (func_num_args() == 4)
			throw new ArgumentTypeMismatchException("{}.find({})", $obj, $sub, $start, $end);

	}

	private static function call2($obj, $sub)
	{
		if (is_string($obj))
		{
			$pos = strpos($obj, $sub);
			return is_bool($pos) ? -1 : $pos;
		}
		elseif (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$pos = array_search($sub, $obj);
			return is_bool($pos) ? -1 : $pos;
		}
	}

	private static function call3($obj, $sub, $start)
	{
		if (is_string($obj))
		{
			$start = Utils::getSliceStartPos(strlen($obj), $start);
			$pos = strpos($obj, $sub, $start);
			return is_bool($pos) ? -1 : $pos;
		}
		elseif (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$start = Utils::getSliceStartPos(count($obj), $start);
			if ($start != 0)
				$obj = array_slice($obj, count($obj));
			$pos = array_search($sub, $obj);
			return is_bool($pos) ? -1 : $pos;
		}
	}

	private static function call4($obj, $sub, $start, $end)
	{
		if (is_string($obj))
		{
			$start = Utils::getSliceStartPos(strlen($obj), $start);
			$end = Utils::getSliceEndPos(strlen($obj), $end);
			$pos = strpos($obj, $sub, $start);
			if ($pos === False || $pos + strlen($sub) > $end)
				return -1;
			return $pos;
		}
		elseif (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$start = Utils::getSliceStartPos(count($obj), $start);
			$end = Utils::getSliceEndPos(count($obj), $end);
			if ($start != 0)
				$obj = array_slice($obj, $start, $end - $start);
			$pos = array_search($sub, $obj);
			if (!is_bool($pos))
				$pos += $start;
			return is_bool($pos) ? -1 : $pos;
		}
	}

}

?>
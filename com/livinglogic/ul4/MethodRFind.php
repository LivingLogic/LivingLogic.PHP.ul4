<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodRFind implements Method
{
	public function getName()
	{
		return "rfind";
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
				throw new ArgumentCountMismatchException("method", "rfind", count($args), 1, 3);
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
			throw new ArgumentTypeMismatchException("{}.rfind({})", $obj, $sub);
		elseif (func_num_args() == 3)
			throw new ArgumentTypeMismatchException("{}.rfind({})", $obj, $sub, $start);
		elseif (func_num_args() == 4)
			throw new ArgumentTypeMismatchException("{}.rfind({})", $obj, $sub, $start, $end);

	}

	private static function call2($obj, $sub)
	{
		if (is_string($obj))
		{
			$pos = strrpos($obj, $sub);
			return is_bool($pos) ? -1 : $pos;
		}
		elseif (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$pos = -1;
			$lensub = mb_strlen($sub, \com\livinglogic\ul4on\Utils::$encoding);
			for ($i = count($obj) - $lensub; $i >= 0; $i--)
			{
				if ($obj[$i] === $sub)
				{
					$pos = $i;
					break;
				}
			}
			return $pos ;
		}
	}

	private static function call3($obj, $search, $start)
	{
		if (is_string($obj))
		{
			$start = Utils::getSliceStartPos(mb_strlen($obj, \com\livinglogic\ul4on\Utils::$encoding), $start);
			$pos = strrpos($obj, $sub);
			if ($pos < $start)
				return -1;
			return is_bool($pos) ? -1 : $pos;
		}
		elseif (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$start = Utils::getSliceStartPos(count($obj), $start);
			$pos = -1;
			for ($i = count($obj) - 1; $i >= 0; $i--)
			{
				if ($search === $obj[$i])
				{
					$pos = $i;
					break;
				}
			}
			return $pos;
		}
	}

	private static function call4($obj, $search, $start, $end)
	{
		if (is_string($obj))
		{
			$start = Utils::getSliceStartPos(mb_strlen($obj, \com\livinglogic\ul4on\Utils::$encoding), $start);
			$end = Utils::getSliceStartPos(mb_strlen($obj, \com\livinglogic\ul4on\Utils::$encoding), $end);
			$end -= mb_strlen($search, \com\livinglogic\ul4on\Utils::$encoding);
			if ($end < 0)
				return -1;

			$result = -1;
			$lensearch = mb_strlen($search, \com\livinglogic\ul4on\Utils::$encoding);
			for ($i = count($obj) - count($search); $i >= 0; $i--)
			{
				if (mb_substr($obj, $i, $lensearch, \com\livinglogic\ul4on\Utils::$encoding) === $search)
				{
					$result = $i;
					break;
				}
			}

			if ($result < $start)
				return -1;
			return $result;
		}
		elseif (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$start = Utils::getSliceStartPos(count($obj), $start);
			$end = Utils::getSliceStartPos(count($obj), $end);
			$obj = array_slice($obj, $start, $end - $start);

			$pos = -1;
			for ($i = count($obj) - 1; $i >=0; $i--)
			{
				if ($obj[$i] === $search)
				{
					$pos = $i;
					break;
				}
			}

			if ($pos != -1)
				$pos += $start;
			return $pos;
		}
	}
}

?>
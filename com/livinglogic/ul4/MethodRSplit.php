<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodRSplit implements Method
{
	public function getName()
	{
		return "rsplit";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			case 1:
				return $this->call($obj, $args[0]);
			case 2:
				return $this->call($obj, $args[0], $args[1]);
			default:
				throw new ArgumentCountMismatchException("method", "rsplit", count($args), 0, 2);
		}
	}

	public function call($obj)
	{
		$arg1 = func_num_args() > 1 ? func_get_arg(1) : null;
		$arg2  = func_num_args() == 3 ? func_get_arg(2) : null;

		$separator = null;
		$maxsplit = null;

		if (is_string($obj))
		{
			if ($arg1 == null && $arg2 == null)
				return preg_split("/\s/", $obj);
		}
		else
			throw new ArgumentTypeMismatchException("{}.rsplit()", $obj);

		if (func_num_args() == 2)
		{
			if (is_int($arg1))
			{
				$maxint = $arg1;
				$this->call2($obj, $maxsplit);
			}
			elseif (is_string($arg1))
			{
				$separator = $arg1;
				return $this->call3($obj, $separator, 0x7fffffff);
			}
		}
		elseif (func_num_args() == 3)
		{
			$separator = $arg1;
			$maxsplit = $arg2;
			if (is_string($obj))
			{
				if (is_null($separator))
					return $this->call2($obj, Utils::toInteger($maxsplit));
				else if (is_string($separator))
					return $this->call3($obj, $separator, Utils::toInteger($maxsplit));
			}
			throw new ArgumentTypeMismatchException("{}.rsplit({}, {})", $obj, $separator, $maxsplit);
		}
	}

	private function call2($obj, $maxsplit)
	{
		$result = array();
		$start = $end = strlen($obj) - 1;

		while ($maxsplit-- > 0)
		{
			while ($start >= 0 && preg_match("/\s/", $obj[$start]))
				--$start;
			if ($start < 0)
				break;
			$end = $start--;
			while ($start >= 0 && !preg_match("/\s/", $obj[$start]))
				--$start;
			if ($start != $end)
				array_unshift($result, substr($obj, $start + 1, $end - $start));
		}
		if ($start >= 0)
		{
			while ($start >= 0 && preg_match("/\s/", $obj[$start]))
				--$start;
			if ($start >= 0)
				array_unshift($result, substr($obj, 0, $start + 1));
		}

		return $result;
	}

	private function call3($obj, $separator, $maxsplit)
	{
		if (strlen($separator) == 0)
			throw new \Exception("empty separator not supported");

		$result = array();
		$start = strlen($obj);
		$end = $start;
		$seplen = strlen($separator);
		while ($maxsplit-- > 0)
		{
			$start = strrpos(substr($obj, 0, $end), $separator, 0);
			if (is_bool($start))
				break;
			array_unshift($result, substr($obj, $start + $seplen, $end - $start - $seplen));
			$end = $start;
		}
		array_unshift($result, substr($obj, 0, $end));
		return $result;
	}
}

?>
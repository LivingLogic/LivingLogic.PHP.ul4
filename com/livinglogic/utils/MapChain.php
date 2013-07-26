<?php

namespace com\livinglogic\utils;

class MapChain
{
	private $first = array();
	private $second = null;

	public function __construct(&$first, &$second)
	{
		$this->setFirst($first);
		$this->setSecond($second);
	}

	public function put($key, $value)
	{
		$this->first[$key] = $value;
	}

	public function get($key)
	{
		if (array_key_exists($key, $this->first))
			return $this->first[$key];

		if (is_array($this->second))
		{
			if (array_key_exists($key, $this->second))
			{
				return $this->second[$key];
			}
			else
				return null;
		}
		else if ($this->second instanceof MapChain)
			return $this->second->get($key);

		return null;
	}

	public function array_key_exists($key)
	{
		if (array_key_exists($key, $this->first))
			return true;
		else
		{
			if (is_array($this->second))
			{
				if (array_key_exists($key, $this->second))
					return true;
				else
					return false;
			}
			else
			{
				return $this->second->array_key_exists($key);
			}
		}
	}

	public function &getFirst()
	{
		return $this->first;
	}

	public function setFirst(&$first)
	{
		if (!is_array($first))
			throw new \Exception("first has to be an array not a " . gettype($first));

		$oldFirst = $this->first;
		$this->first = $first;

		return $oldFirst;
	}

	public function &getSecond()
	{
		return $this->second;
	}

	public function setSecond(&$second)
	{
		if (!is_null($second) && (!(is_array($second) || $second instanceof MapChain)))
			throw new \Exception("second has to be an array or a MapChain not a " . gettype($first));

		$oldSecond = $this->second;
		$this->second = $second;

		return $oldSecond;
	}
}

?>
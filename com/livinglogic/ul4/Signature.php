<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Signature implements \IteratorAggregate // implements Iterable<ArgumentDescription>
{
	protected $name;
	protected $arguments; //LinkedHashMap<String, ArgumentDescription> arguments;
	protected $remainingArgumentsName;
	protected $remainingKeywordArgumentsName;

	/**
	 * Marker objects that specify certain types of arguments.
	 */
	public static $required;
	public static $remainingArguments;
	public static $remainingKeywordArguments;

	public static function static_init()
	{
		self::$required = new Signature("required", array());
		self::$remainingArguments = new Signature("remainingArguments", array());
		self::$remainingKeywordArguments = new Signature("remainingKeywordArguments", array());
	}

	public function __construct($name, $args)
	{
		$this->name = $name;
		$this->arguments = array(); //new LinkedHashMap<String, ArgumentDescription>();
		$this->remainingArgumentsName = null;
		$this->remainingKeywordArgumentsName = null;

		$argname = null;
		for ($i = 0; $i < count($args); ++$i)
		{
			if ($i%2 == 0)
				$argname = $args[$i];
			else
			{
				if ($args[$i] === self::$required)
					$this->add($argname);
				else if ($args[$i] === self::$remainingArguments)
					$this->remainingArgumentsName = $argname;
				else if ($args[$i] === self::$remainingKeywordArguments)
					$this->remainingKeywordArgumentsName = $argname;
				else
					$this->add($argname, $args[$i]);
			}
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function add($name)
	{
		if (func_num_args() == 1)
			$this->arguments[$name] = new ArgumentDescription($name, count($this->arguments));
		else
		{
			$defaultValue = func_get_arg(1);
			$this->arguments[$name] = new ArgumentDescription($name, count($this->arguments), $defaultValue);
		}
	}

	public function getIterator()
	{
		return new \ArrayIterator(array_values($this->arguments));
	}

	public function size()
	{
		return count($this->arguments);
	}

	public function containsArgumentNamed($argName)
	{
		return array_key_exists($argName, $this->arguments);
	}

	public function makeArgumentArray($args, $kwargs)
	{
		$realSize = $this->size();
		$remainingArgumentsPos = -1;
		$remainingKeywordArgumentsPos = -1;

		if (!is_null($this->remainingArgumentsName))
			$remainingArgumentsPos = $realSize++;
		if (!is_null($this->remainingKeywordArgumentsName))
			$remainingKeywordArgumentsPos = $realSize++;

		$realargs = array();

		$i = 0;
		foreach ($this as $argDesc)
		{
			$argName = $argDesc->getName();
			$argValue = null;
			if (array_key_exists($argName, $kwargs))
				$argValue = $kwargs[$argName];
			// argument has been specified via keyword
			if (!is_null($argValue) || array_key_exists($argName, $kwargs))
			{
				if ($i < count($args))
					// argument has been specified as a positional argument too
					throw new DuplicateArgumentException($this, $argDesc);
				$realargs[$i] = $argValue;
			}
			else
			{
				if ($i < count($args))
					// argument has been specified as a positional argument
					$realargs[$i] = $args[$i];
				else if ($argDesc->hasDefaultValue())
					// we have a default value for this argument
					$realargs[$i] = $argDesc->getDefaultValue();
				else
					throw new MissingArgumentException($this, $argDesc);
			}
			++$i;
		}

		// Handle additional positional arguments
		// if there are any, and we suport a "*" argument, put the remaining arguments into this argument as a list, else complain
		$expectedArgCount = $this->size();
		if (!is_null($this->remainingArgumentsName))
		{
			$realargs[$remainingArgumentsPos] = (count($args) > $expectedArgCount) ?
				array_slice($args, count($this->arguments), count($args) - count($this->arguments)) : array();
		}
		else
		{
			if (count($args) > $expectedArgCount)
				throw new TooManyArgumentsException($this, count($args));
		}

		// Handle additional keyword arguments
		// if there are any, and we suport a "**" argument, put the remaining keyword arguments into this argument as a map, else complain
		if (!is_null($this->remainingKeywordArgumentsName))
		{
			$realRemainingKeywordArguments = array();
			foreach ($kwargs as $kwargname => $value)
			{
				if (!$this->containsArgumentNamed($kwargname))
				{
					$this->realRemainingKeywordArguments[$kwargname] = $kwargs[$kwargname];
				}
			}
			$realargs[$remainingKeywordArgumentsPos] = $realRemainingKeywordArguments;
		}
		else
		{
			foreach ($kwargs as $kwargname => $value)
			{
				if (!$this->containsArgumentNamed($kwargname))
					throw new UnsupportedArgumentNameException($this, $kwargname);
			}
		}

		return $realargs;
	}
}

Signature::static_init();

?>

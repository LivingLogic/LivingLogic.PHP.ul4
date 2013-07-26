<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class CallFunc extends _Callable
{
	protected $obj;

	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;
	}

	public function getType()
	{
		return "callfunc";
	}

	public function evaluate($context)
	{
		$realobj = $this->obj->evaluate($context);

		$realArgs = null; // array()
		if (!is_null($this->remainingArguments))
		{
			$realRemainingArguments = $this->remainingArguments->evaluate($context);
			if (!\com\livinglogic\ul4on\Utils::isList($this->realRemainingArguments))
				throw new RemainingArgumentsException(realobj);

			$argsSize = count($this->arguments);
			$realArgs = array();

			for ($i = 0; $i < $argsSize; ++$i)
				array_push($realArgs, $this->arguments[$i]->evaluate($context));

			for ($i = 0; $i < count($realRemainingArguments); ++$i)
				array_push($realArgs, $realRemainingArguments[$i]);
		}
		else
		{
			$realArgs = array();

			for ($i = 0; $i < count($this->arguments); ++$i)
				array_push($realArgs, $this->arguments[$i]->evaluate($context));
		}

		$realKWArgs = array();

		foreach ($this->keywordArguments as $arg)
			$realKWArgs[$arg->getName()] = $arg->getArg()->valuate($context);

		if (!is_null($this->remainingKeywordArguments))
		{
			$realRemainingKWArgs = $this->remainingKeywordArguments->evaluate($context);
			if (!\com\livinglogic\ul4on\Utils::isDict($realRemainingKWArgs))
				throw new RemainingKeywordArgumentsException($realobj);
			foreach ($realRemainingKWArgs as $argumentName => $value)
			{
				if (!is_string($argumentName))
					throw new RemainingKeywordArgumentsException($realobj);
				if (array_key_exists($argumentName, $realKWArgs))
					throw new DuplicateArgumentException($realobj, $argumentName);
				$realKWArgs[$argumentName] = $value;
			}
		}

		return $this->call($context, $realobj, $realArgs, $realKWArgs);
	}

	public function call($arg0, $arg1, $arg2)
	{
		if (func_num_args() == 3)
		{
			$obj = $arg0;
			$args = $arg1;
			$kwargs = $arg2;
			$obj->callUL4($args, $kwargs);
		}
		else if (func_num_args() == 4)
		{
			$context = $arg0;
			$obj = $arg1;
			$args = $arg2;
			$kwargs = func_get_arg(3);

			if ($obj instanceof UL4Call)
				return $obj->callUL4($args, $kwargs);
			else if ($obj instanceof UL4CallWithContext)
				return $obj->callUL4($context, $args, $kwargs);
			else
				throw new NotCallableException($obj);
		}
		else
		{
			throw new \Exception("CallFunc.call has to be called with 3 or 4 arguments but was called with " . func_num_args() . "!");
		}
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->obj);
		$encoder->dump($this->args);

		$kwarglist = array();
		foreach ($this->kwargs as $arg)
			array_push($kwarglist, array($arg->getName(), $arg->getArg()));

		$encoder->dump($kwarglist);
		$encoder->dump($this->remargs);
		$encoder->dump($this->remkwargs);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->obj = $decoder->load();
		$this->arguments = $decoder->load();

		$kwarglist = $decoder->load();
		foreach ($kwarglist as $arg)
			$this->append($arg[0], $arg[1]);

		$this->remainingArguments = $decoder->load();
		$this->remainingKeywordArguments = $decoder->load();
	}

	protected static $attributes;

	public static function static_init()
	{
		$attributes = array_unique(array_merge(_Callable::$attributes, array("obj")));
	}

	public function getAttributeNamesUL4()
	{
		return self::attributes;
	}

	public function getItemStringUL4($key)
	{
		if ("obj" === $key)
			return $this->obj;
		else
			return parent::getItemStringUL4($key);
	}

}

CallFunc::static_init();

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.callfunc", "\com\livinglogic\ul4\CallFunc");

?>

<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class CallFunc extends _Callable
{
	/*
	protected $_function;
	protected $args = array();

	private static $functions = null;

	public static function init()
	{
		self::$functions = array(
			"now" => new FunctionNow(),
			"utcnow" => new FunctionUTCNow(),
			"vars" => new FunctionVars(),
			"random" => new FunctionRandom(),
			"xmlescape" => new FunctionXMLEscape(),
			"csv" => new FunctionCSV(),
			"str" => new FunctionStr(),
			"repr" => new FunctionRepr(),
			"int" => new FunctionInt(),
			"float" => new FunctionFloat(),
			"bool" => new FunctionBool(),
			"len" => new FunctionLen(),
			"enumerate" => new FunctionEnumerate(),
			"enumfl" => new FunctionEnumFL(),
			"isfirstlast" => new FunctionIsFirstLast(),
			"isfirst" => new FunctionIsFirst(),
			"islast" => new FunctionIsLast(),
			"isnone" => new FunctionIsNone(),
			"isstr" => new FunctionIsStr(),
			"isint" => new FunctionIsInt(),
			"isfloat" => new FunctionIsFloat(),
			"isbool" => new FunctionIsBool(),
			"isdate" => new FunctionIsDate(),
			"islist" => new FunctionIsList(),
			"isdict" => new FunctionIsDict(),
			"istemplate" => new FunctionIsTemplate(),
			"iscolor" => new FunctionIsColor(),
			"chr" => new FunctionChr(),
			"ord" => new FunctionOrd(),
			"hex" => new FunctionHex(),
			"oct" => new FunctionOct(),
			"bin" => new FunctionBin(),
			"abs" => new FunctionAbs(),
			"range" => new FunctionRange(),
			"sorted" => new FunctionSorted(),
			"type" => new FunctionType(),
			"get" => new FunctionGet(),
 			"asjson" => new FunctionAsJSON(),
 			"asul4on" => new FunctionAsUL4ON(),
 			"reversed" => new FunctionReversed(),
			"randrange" => new FunctionRandRange(),
			"randchoice" => new FunctionRandChoice(),
			"format" => new FunctionFormat(),
 			"zip" => new FunctionZip(),
 			"rgb" => new FunctionRGB(),
 			"hls" => new FunctionHLS(),
			"hsv" => new FunctionHSV(),
			"all" => new FunctionAll(),
			"any" => new FunctionAny(),
			"date" => new FunctionDate(),
			"fromjson" => new FunctionFromJSON(),
			"fromul4on" => new FunctionFromUL4ON(),
			"isdefined" => new FunctionIsDefined(),
			"isundefined" => new FunctionIsUndefined(),
			"ismonthdelta" => new FunctionIsMonthDelta(),
			"istimedelta" => new FunctionIsTimeDelta(),
			"max" => new FunctionMax(),
			"min" => new FunctionMin(),
			"monthdelta" => new FunctionMonthDelta(),
			"timedelta" => new FunctionTimeDelta(),
			"urlquote" => new FunctionURLQuote(),
			"urlunquote" => new FunctionURLUnquote()
		);
	}
	*/

	/*
	public function append($arg)
	{
		array_push($this->args, $arg);
	}

	public function toString($indent)
	{
		$buffer = "";

		$buffer .= "callfunc(";
		$buffer .= Utils::repr($this->_function->getName());

		foreach ($this->args as $arg)
		{
			$buffer .= ", ";
			$buffer .= $arg;
		}
		$buffer .= ")";

		$buffer;
	}

	public function getType()
	{
		return "callfunc";
	}

	public function evaluate($context)
	{
		$realArgs = array();

		for ($i = 0; $i < count($this->args); $i++)
			array_push($realArgs, $this->args[$i]->evaluate($context));

		return $this->_function->call($context, $realArgs);
	}

	private static function getFunction($funcname)
	{
		$_function = null;

		if (! (is_string($funcname) || is_int($funcname)))
			print "CallFunc.getFunction: funcname is ". \gettype($funcname) . "\n";

		if (array_key_exists($funcname, self::$functions))
			$_function = self::$functions[$funcname];

		if ($_function == null)
			throw new UnknownFunctionException($funcname);

		return $_function;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->_function.getName());
		$encoder->dump($this->args);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->_function = self::getFunction($decoder->load());
		$this->args = $decoder->load();
	}
	*/

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("funcname", new ValueMaker(){public Object getValue(Object object){return ((CallFunc)object).function.getName();}});
			v.put("args", new ValueMaker(){public Object getValue(Object object){return ((CallFunc)object).args;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/

	protected $obj;
	protected $args;
	protected $kwargs;
	protected $remargs;
	protected $remkwargs;

	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;
		$this->args = array();
		$this->kwargs = array();
		$this->remargs = null;
		$this->remkwargs = null;
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

			for ($i = 0; $i < count($realArgs); ++$i)
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
				$obj->callUL4($args, $kwargs);
			else if ($obj instanceof UL4CallWithContext)
				$obj->callUL4($context, $args, $kwargs);
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
		$this->args = $decoder->load();

		$kwarglist = $decoder->load();
		foreach ($kwarglist as $arg)
			$this->append($arg[0], $arg[1]);

		$this->remargs = $decoder->load();
		$this->remkwargs = $decoder->load();
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

<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class CallFunc extends AST
{
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
			"date" => new FunctionDate()
		);
	}


	public function __construct($location=null, $_function=null)
	{
		parent::__construct($location);

		if ($_function instanceof _Function)
			$this->_function = $_function;
		else if (is_string($_function))
			$this->function = self::getFunction($_function);
	}

	public function append($arg)
	{
		array_push($this->args, $arg);
	}

	public function toString($indent)
	{
		$buffer = "";

		$buffer .= "callfunc(";
		$buffer .= Utils.repr($_function.getName());

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
}

CallFunc::init();

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.callfunc", "\com\livinglogic\ul4\CallFunc");

?>

<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class CallMeth extends AST
{
	protected $methname;
	protected $obj;
	protected $args = array();
	protected $kwargs = array();
	protected $remargs = null;
	protected $remkwargs = null;

	private static $methods = null;

	public static function init()
	{
		self::$methods = array(
			"split" => new MethodSplit(),
			"rsplit" => new MethodRSplit(),
			"strip" => new MethodStrip(),
			"lstrip" => new MethodLStrip(),
			"rstrip" => new MethodRStrip(),
			"upper" => new MethodUpper(),
			"lower" => new MethodLower(),
			"capitalize" => new MethodCapitalize(),
			"items" => new MethodItems(),
			"values" => new MethodValues(),
			"microsecond" => new MethodMicrosecond(),
			"hour" => new MethodHour(),
			"minute" => new MethodMinute(),
			"second" => new MethodSecond(),
			"isoformat" => new MethodISOFormat(),
			"mimeformat" => new MethodMIMEFormat(),
			"r" => new MethodR(),
			"g" => new MethodG(),
			"b" => new MethodB(),
			"a" => new MethodA(),
			"hls" => new MethodHLS(),
			"hlsa" => new MethodHLSA(),
			"hsv" => new MethodHSV(),
			"hsva" => new MethodHSVA(),
			"lum" => new MethodLum(),
			"day" => new MethodDay(),
			"month" => new MethodMonth(),
			"year" => new MethodYear(),
			"weekday" => new MethodWeekday(),
			"yearday" => new MethodYearday(),
/*
			"render" => new MethodRender(),
			"renders" => new MethodRenderS(),
*/
			"startswith" => new MethodStartsWith(),
			"endswith" => new MethodEndsWith(),
			"find" => new MethodFind(),
			"rfind" => new MethodRFind(),
			"get" => new MethodGet(),
			"withlum" => new MethodWithLum(),
			"witha" => new MethodWithA(),
			"join" => new MethodJoin(),
			"replace" => new MethodReplace()
		);
	}

	public function __construct($location=null, $start=0, $end=0, $obj=null, $methname=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;

		$this->methname = $methname;
		$this->args = array();
		$this->kwargs = array();
		$this->remargs = null;
		$this->remkwargs = null;
	}

	public function append($arg)
	{
		array_push($this->args, $arg);
	}

	public function toString($indent)
	{
		$buffer = "";

		$buffer .= "callmeth(";
		$buffer .= $this->obj;
		$buffer .= ", ";
		$buffer .= FunctionRepr::call($this->methname);
		foreach ($this->args as $arg)
		{
			$buffer .= ", ";
			$buffer .= $arg;
		}
		$buffer .= ")";
		return $buffer;
	}

	public function getType()
	{
		return "callmeth";
	}

	public function evaluate($context)
	{
		$obj = $this->obj->evaluate($context);

		$realArgs = array();

		foreach ($this->args as $arg)
			array_push($realArgs, $arg->evaluate($context));
		return $this->getMethod($this->methname)->evaluate($context, $obj, $realArgs);
	}

	private static function getMethod($methname)
	{
		$method = null;

		if (array_key_exists($methname, self::$methods))
			$method = self::$methods[$methname];

		if ($method == null)
			throw new UnknownFunctionException($methname);

		return $method;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->methname);
		$encoder->dump($this->obj);
		$encoder->dump($this->args);
		$encoder->dump($this->kwargs);
		$encoder->dump($this->remargs);
		$encoder->dump($this->remkwargs);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->methname = $decoder->load();
		$this->obj = $decoder->load();
		$this->args = $decoder->load();
		$this->kwargs = $decoder->load();
		$this->remargs = $decoder->load();
		$this->remkwargs = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("obj", new ValueMaker(){public Object getValue(Object object){return ((CallMeth)object).obj;}});
			v.put("methname", new ValueMaker(){public Object getValue(Object object){return ((CallMeth)object).method.getName();}});
			v.put("args", new ValueMaker(){public Object getValue(Object object){return ((CallMeth)object).args;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

CallMeth::init();

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.callmeth", "\com\livinglogic\ul4\CallMeth");

?>

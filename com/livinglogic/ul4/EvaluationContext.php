<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

use \com\livinglogic\utils\MapChain as MapChain;


class EvaluationContext implements \com\livinglogic\utils\Closeable, \com\livinglogic\utils\CloseableRegistry
{
	protected $buffer;
	protected $variables;

	/**
	 * A list of cleanup tasks that have to be done, when the
	 * {@code EvaluationContext} is no longer used
	 */
	private $closeables;

	/**
	 * The currently executing template object
	 */
	private $template;

	/**
	 * A {@link com.livinglogic.utils.MapChain} object chaining all variables:
	 * The user defined ones from {@link #variables} and the map containing the
	 * global functions.
	 */
	protected $allVariables;

	private static $functions;

	public static function static_init()
	{
		self::$functions = array(
				"now" => new FunctionNow(),
				"utcnow" => new FunctionUTCNow(),
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

	public function __construct($variables=null)
	{
		$this->buffer = "";
		if ($variables == null)
			$variables = array();
		$this->variables = $variables;
		$this->template = null;
		$this->allVariables = new MapChain($variables, self::$functions);
		$this->closeables = array();
	}

	public function close()
	{
		foreach ($this->closeables as $closeable)
		{
			$closeable->close();
		}
	}

	public function registerCloseable($closeable)
	{
		array_push($this->closeables, $closeable);
	}

	public function &getVariables()
	{
		return $this->variables;
	}

	public function &getAllVariables()
	{
		return $this->allVariables;
	}

	public function setVariables($variables)
	{
		if (is_null($variables))
			$variables = array();
		$result = $this->variables;
		$this->variables = $variables;
		$this->allVariables->setFirst($variables);

		return $result;
	}

	public function getOutput()
	{
		return $this->buffer;
	}

	public function write($string)
	{
		$this->buffer .= $string;
	}

	public function put($key, $value)
	{
		$this->variables[$key] = $value;
	}

	public function get($key)
	{
		$result = $this->allVariables->get($key);

		if (is_null($result) && !$this->allVariables->array_key_exists($key))
			return new UndefinedVariable($key);

		return $result;
	}

	public function remove($key)
	{
		unset($this->variables[$key]);
	}
}

EvaluationContext::static_init();

?>
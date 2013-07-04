<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class GetSlice extends AST
{
	var $obj;
	var $index1;
	var $index2;

	public function __construct($location=null, $start=0, $end=0, $obj=null, $index1=null, $index2=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;
		$this->index1 = $index1;
		$this->index2 = $index2;
	}

	public function toString($indent)
	{
		return "getslice(" . $this->obj . ", " . $this->index1 . ", " . $this->index2 . ")";
	}

	public function getType()
	{
		return "getslice";
	}

	public function evaluate($context)
	{
		return $this->call($this->obj->evaluate($context), $this->index1 != null ? $this->index1->evaluate($context) : null, $this->index2 != null ? $this->index2->evaluate($context) : null);
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->obj);
		$encoder->dump($this->index1);
		$encoder->dump($this->index2);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->obj = $decoder->load();
		$this->index1 = $decoder->load();
		$this->index2 = $decoder->load();
	}

	/*
	public static Object call(List obj, int startIndex, int endIndex)
	{
		int size = obj.size();
		int start = Utils.getSliceStartPos(size, startIndex);
		int end = Utils.getSliceEndPos(size, endIndex);
		if (end < start)
			end = start;
		return obj.subList(start, end);
	}

	public static Object call(String obj, int startIndex, int endIndex)
	{
		int size = obj.length();
		int start = Utils.getSliceStartPos(size, startIndex);
		int end = Utils.getSliceEndPos(size, endIndex);
		if (end < start)
			end = start;
		return StringUtils.substring(obj, start, end);
	}
	*/

	public function call($obj, $startIndex, $endIndex)
	{
		if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$start = $startIndex != null ? FunctionInt::call($startIndex) : 0;
			$end = $endIndex != null ? FunctionInt::call($endIndex) : count($obj);

			$size = count($obj);
			$start = Utils::getSliceStartPos($size, $start);
			$end = Utils::getSliceEndPos($size, $end);
			if ($end < $start || $start >= $size)
				return array();
			return array_slice($obj, $start, $end - $start);
		}
		else if (is_string($obj))
		{
			$start = $startIndex != null ? FunctionInt::call($startIndex) : 0;
			$end = $endIndex != null ? FunctionInt::call($endIndex) : strlen($obj);

			$size = strlen($obj);
			$start = Utils::getSliceStartPos($size, $start);
			$end = Utils::getSliceEndPos($size, $end);
			if ($end < $start || $start >= $size)
				return "";
			return substr($obj, $start, $end - $start);
		}
		throw new ArgumentTypeMismatchException("{}[{}:{}]", $obj, $startIndex, $endIndex);
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("obj", new ValueMaker(){public Object getValue(Object object){return ((GetSlice)object).obj;}});
			v.put("index1", new ValueMaker(){public Object getValue(Object object){return ((GetSlice)object).index1;}});
			v.put("index2", new ValueMaker(){public Object getValue(Object object){return ((GetSlice)object).index2;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.getslice", "\com\livinglogic\ul4\GetSlice");

?>

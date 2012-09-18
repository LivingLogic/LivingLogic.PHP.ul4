<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class DictItemDict extends DictItem
{
	var $dict;

	function __construct($dict=null)
	{
		$this->dict = $dict;
	}

	function __toString()
	{
		return "**" . $this->dict->__toString();
	}

	function addTo($context, &$dict)
	{
		foreach ($this->dict->evaluate($context) as $key => $value)
		{
			$dict[$key] = $value;
		}
	}

	function object4UL4ON()
	{
		return array($this->dict);
	}
}

?>
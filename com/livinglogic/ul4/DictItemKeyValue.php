<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class DictItemKeyValue extends DictItem
{
	var $key;
	var $value;

	function __construct($key, $value)
	{
		$this->key = $key;
		$this->value = $value;
	}

	function __toString()
	{
		return $this->key->__toString() . ": " . $this->value->__toString();
	}

	function addTo($context, &$dict)
	{
		$dict[$this->key->evaluate($context)] = $this->value->evaluate($context);
	}

	function object4UL4ON()
	{
		return array($this->key, $this->value);
	}
}

?>
<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class _Function implements UL4Call, UL4Name, UL4Type
{
	/*
	public function call($context, $args);

	public function getName();
	*/

	public abstract function nameUL4();

	public function typeUL4()
	{
		return "function";
	}

	private $signature = null;

	protected function makeSignature()
	{
		return new Signature($this->nameUL4());
	}

	private function getSignature()
	{
		if (is_null($this->signature))
			$this->signature = $this->makeSignature();
		return $this->signature;
	}

	public function callUL4($args, $kwargs)
	{
		return $this->evaluate($this->getSignature()->makeArgumentArray($args, $kwargs));
	}

	public abstract function evaluate($args);

}

?>
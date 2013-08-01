<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


abstract class FunctionWithContext implements UL4CallWithContext, UL4Name
{
	public abstract function nameUL4();

	private $signature = null;

	protected function makeSignature()
	{
		return new Signature($this->nameUL4());
	}

	private function getSignature()
	{
		if ($this->signature == null)
			$this->signature = $this->makeSignature();
		return $this->signature;
	}

	public function callUL4($context, $args, $kwargs)
	{
		return $this->evaluate($context, $this->getSignature()->makeArgumentArray($args, $kwargs));
	}

	public abstract function evaluate($context, $args);
}

?>
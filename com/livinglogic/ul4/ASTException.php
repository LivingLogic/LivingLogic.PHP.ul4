<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ASTException extends \Exception
{
	protected $node;

	public function __construct($cause, $node)
	{
		parent::__construct("in " . $node, 0, $cause);
		$this->node = $node;
	}
}

?>
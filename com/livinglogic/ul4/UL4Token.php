<?php

namespace com\livinglogic\ul4;

class UL4Token
{
	protected $start;
	protected $end;
	protected $type;

	public function __construct($start, $end, $type)
	{
		$this->start = $start;
		$this->end = $end;
		$this->type = $type;
	}

	public function getStart()
	{
		return $this->start;
	}

	public function getEnd()
	{
		return $this->end;
	}

	public function getTokenType()
	{
		return $this->type;
	}

	public function toString($indent=0)
	{
		return "token \"" . str_replace("\"", "\\\\\"", $this->type) + "\"";
	}
}

?>

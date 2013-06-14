<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Formatter
{
	private $builder = "";
	private $level = 0;
	private $needsLF = false;

	public function __construct()
	{
	}

	public function indent()
	{
		++$this->level;
	}

	public function dedent()
	{
		--$this->level;
	}

	public function lf()
	{
		$this->needsLF = true;
	}

	public function write($string)
	{
		if ($this->needsLF)
		{
			$this->builder .= "\n";
			for ($i = 0; $i < $this->level; ++$i)
				$this->builder .= "\t";
			$this->needsLF = false;
		}
		$this->builder .= $string;
	}

	public function finish()
	{
		if ($this->needsLF)
			$this->builder .= "\n";
	}

	public function __toString()
	{
		return $this->builder;
	}
}

?>

<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ConditionalBlockBlock extends Block
{
	public function __construct($location=null, $block=null)
	{
		parent::__construct($location);
		if (!is_null($block))
			startNewBlock($block);
	}

	public function getType()
	{
		return "ieie";
	}

	public function toString($indent)
	{
		$buffer = "";
		foreach ($this->content as $item)
			$buffer .= $item->toString($indent);
		return $buffer;
	}

	public function handleLoopControl($name)
	{
		return false;
	}

	public function append($item)
	{
		$this->content[count($this->content-1)]->append($item);
	}

	public function startNewBlock($item)
	{
		if ($item instanceof _If)
		{
			if (count($this->content) != 0)
				throw new \Exception("if must be first in if/elif/else chain");
		}
		else if ($item instanceof ElIf)
		{
			if (count($this->content) == 0)
				throw new BlockException("elif can't be first in if/elif/else chain");
			$last = $this->content.get(count($this->content)-1);
			if ($last instanceof _Else)
				throw new BlockException("elif can't follow else in if/elif/else chain");
		}
		else if ($item instanceof _Else)
		{
			if (count($this->content) == 0)
				throw new BlockException("else can't be first in if/elif/else chain");
			$last = $this->content.get(count($this->content)-1);
			if ($last instanceof _Else)
				throw new BlockException("duplicate else in if/elif/else chain");
		}
		array_push($this->content, $item);
	}

	public function finish($template, $endlocation)
	{
		parent::finish($template, $endlocation);
		$this->content[count($this->content)-1]->endlocation = $endlocation;
		$type = trim($endlocation->getCode());
		if (!is_null($type) && strlen($type) != 0 && $type != "if")
			throw new BlockException("if ended by end" + type);
	}

	public function evaluate($context)
	{
		foreach ($this->content as $item)
		{
			if ($item->hasToBeExecuted($context))
				return $item->evaluate($context);
		}
		return null;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.ieie", "\com\livinglogic\ul4\ConditionalBlockBlock");

?>
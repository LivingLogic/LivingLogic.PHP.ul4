<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ForUnpack extends _For
{
	protected $iternames = array();

	public function __construct($location=null, $container=null)
	{
		parent::__construct($location, $container);
	}

	public function appendName($itername)
	{
		array_push($this->iternames, $itername);
	}

	public function getType()
	{
		return "foru";
	}

	public function toString($indent)
	{
		$buffer = "";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "for (";
		$count = 0;
		foreach ($his->iternames as $itername)
		{
			++$count;
			$buffer .= $itername;
			if ($count == 1 || $count != count($this->iternames))
				$buffer .= ", ";
		}
		$buffer .= ") in ";
		$buffer .= $this->container->toString($indent);
		$buffer .= "\n";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "{\n";
		++$indent;
		foreach ($this->content as $item)
			$buffer .= $item->toString($indent);
		--$indent;
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "}\n";
		return $buffer;
	}

	public function unpackLoopVariable($context, $item)
	{
		$varnames = $this->iternames;
		if (func_num_args() == 3)
			$varnames = func_get_arg(2);

		$itemIter = Utils::iterator($item);
		$nameIter = Utils::iterator($varnames);

		$count = 0;

		for (;;)
		{
			if ($itemIter->valid())
			{
				if ($nameIter->valid())
				{
					$context->put($nameIter->current(), $itemIter->current());
					++$count;
				}
				else
				{
					throw new \Exception("mismatched for loop unpacking: " . $count . " varnames, " . $count . "+ items");
				}
			}
			else
			{
				if ($nameIter->valid())
				{
					throw new \Exception("mismatched for loop unpacking: " . $count . "+ varnames, " . $count . " items");
				}
				else
				{
					break;
				}
			}

			$nameIter->next();
			$itemIter->next();
		}
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder.dump($this->iternames);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->iternames = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.foru", "\com\livinglogic\ul4\ForUnpack");

?>

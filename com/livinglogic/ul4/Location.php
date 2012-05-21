<?php

namespace com\livinglogic\ul4;

/*
import java.util.Map;
import java.util.HashMap;
import java.io.IOException;
*/

//include_once 'com/livinglogic/utils/ObjectAsMap';
include_once 'com/livinglogic/ul4on/UL4ONSerializable.php';
include_once 'com/livinglogic/ul4on/Encoder.php';
include_once 'com/livinglogic/ul4on/Decoder.php';
include_once 'com/livinglogic/ul4/Utils.php';
include_once 'com/livinglogic/ul4on/Utils.php';

use com\livinglogic\ul4\Utils as Utils;
use com\livinglogic\ul4on\UL4ONSerializable as UL4ONSerializable;

class Location /*extends ObjectAsMap*/ implements UL4ONSerializable
{
	public $source;
	protected $type;
	public $starttag;
	public $endtag;
	public $startcode;
	public $endcode;

	public function __construct($source=NULL, $type=NULL, $starttag=NULL, $endtag=NULL, $startcode=NULL, $endcode=NULL)
	{
		$this->source = $source;
		$this->type = $type;
		$this->starttag = $starttag;
		$this->endtag = $endtag;
		$this->startcode = $startcode;
		$this->endcode = $endcode;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getTag()
	{
		return substr($this->source, $this->starttag, $this->endtag - $this->starttag);  // TODO zweites Argument?
	}

	public function getCode()
	{
		return substr($this->source, $this->startcode, $this->endcode - $this->startcode);  // TODO zweites Argument?
	}

	private function toString()
	{
		$line = 1;
		$col;
		$lastLineFeed = strrpos($this->source, "\n", starttag);

		if (is_bool($lastLineFeed) && !$lastLineFeed)
		{
			$col = $this->starttag + 1;
		}
		else
		{
			$col = 1;
			for ($i = 0; $i < $this->starttag; ++$i)
			{
				if ($this->source{$i} == '\n')
				{
					++$line;
					$col = 0;
				}
				++$col;
			}
		}
		$tagType = ($this->type != null) ? "<?" + $this->type + "?> tag" : "literal";

		$source = null;

		if ($type != null)
		{
			$tag = Utils::repr($this->getTag());
			$source = ": " . substr($tag, 1, strlen($tag) - 1);
		}
		else
			$source = "";

		return $tagType . " at position " . ($this->starttag+1) . " (line " . $line . ", col " . $col . ")" . $source;
	}

	public function __toString()
	{
		return $this->toString();
	}

	public function getUL4ONName()
	{
		return "de.livinglogic.ul4.location";  // ???
	}

	public function dumpUL4ON($encoder)
	{
		$encoder->dump($this->source);
		$encoder->dump($this->type);
		$encoder->dump($this->starttag);
		$encoder->dump($this->endtag);
		$encoder->dump($this->startcode);
		$encoder->dump($this->endcode);
	}

	public function loadUL4ON($decoder)
	{
		$this->source = $decoder->load();
		$this->type = $decoder->load();
		$this->starttag = $decoder->load();
		$this->endtag = $decoder->load();
		$this->startcode = $decoder->load();
		$this->endcode = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>();
			v.put("type", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).getType();
				}
			});
			v.put("starttag", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).starttag;
				}
			});
			v.put("endtag", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).endtag;
				}
			});
			v.put("startcode", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).startcode;
				}
			});
			v.put("endcode", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).endcode;
				}
			});
			v.put("tag", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).getTag();
				}
			});
			v.put("code", new ValueMaker(){
				public Object getValue(Object object){
					return ((Location)object).getCode();
				}
			});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.location", "\com\livinglogic\ul4\Location");

?>
<?php

namespace com\livinglogic\ul4on;

interface UL4ONSerializable
{
	public function getUL4ONName();

	public function dumpUL4ON($encoder);

	public function loadUL4ON($decoder);
}

?>
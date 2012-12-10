<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class TemplateException extends \Exception
{
	protected $template;

	public function __construct($cause, $template)
	{
		parent::__construct(!is_null($template->getName()) ? "in template named " . $template->getName() : "in unnamed template", 0, $cause);
		$this->template = $template;
	}
}

?>
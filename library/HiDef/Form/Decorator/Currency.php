<?php

class HiDef_Form_Decorator_Currency extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		return '<div class="input-prepend"><span class="add-on">USD $</span>'.$content.'</div>';
	}
}

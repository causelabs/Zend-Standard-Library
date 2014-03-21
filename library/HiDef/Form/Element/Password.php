<?php

class HiDef_Form_Element_Password extends Zend_Form_Element_Password
{
	public function __construct($spec, $options=null) {
		parent::__construct($spec, $options);

		// Remove unused decorators
		$this->getDecorator('Label')->setOption('optionalSuffix', ':')
			->setOption('requiredSuffix', ':')
			->setOption('separator', '');
	}
}

?>
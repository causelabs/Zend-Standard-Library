<?php
class HiDef_Form_Element_Note extends Zend_Form_Element_Xhtml
{
	public $helper = 'formNote';

	/**
	 * We override the constructor here (and isValid()) in order to prevent
	 * the isValid() method from stripping notes from the form.
	 */
	public function __construct($spec, $options = null)
	{
		if (is_string($spec) && ((null !== $options) && is_string($options))) {
			$options = array('label' => $options);
		}

		if (!isset($options['ignore'])) {
			$options['ignore'] = true;
		}

		parent::__construct($spec, $options);

		$this->removeDecorator('Errors')
			->removeDecorator('Label')
			->removeDecorator('HtmlTag');
	}

	public function isValid($value) {
		return true;
	}
}

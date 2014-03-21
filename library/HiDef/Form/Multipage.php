<?php

class HiDef_Form_Multipage extends HiDef_Form
{
	protected $_count = 0;

	public function addPages(array $pages = array())
	{
		foreach ($pages as $key => $form) {
			$this->addSubForm($form, $key, $this->_count++);
		}
	}

	public function addPreviousButtonElement(Zend_Form_Subform $subForm)
	{
		$element = new Zend_Form_Element_Submit('_previous');
		$element->removeDecorator('Label')
			->setLabel('Previous')
			->setRequired(false)
			->setIgnore(true);

		$subForm->addElement($element);

		return $this;
	}

	public function addNextButtonElement(Zend_Form_SubForm $subForm)
	{
		$element = new Zend_Form_Element_Submit('_next');
		$element->removeDecorator('Label')
			->setLabel('Save and Continue')
			->setRequired(false)
			->setIgnore(true);

		$subForm->addElement($element);

		return $this;
	}

	public function addSubFormActions(Zend_Form_SubForm $subForm)
	{
		$subForm->setMethod('post');

		return $this;
	}

	public function setSubFormDecorators(Zend_Form_SubForm $subForm)
	{
		$subForm->setDecorators(array(
			'FormElements',
			array('HtmlTag', array(
				'tag' => 'dl',
				'class' => 'zend_form'
			)),
			'Form',
		));

		return $this;
   	}

	public function prepareSubForm(Zend_Form_SubForm $subForm)
	{

		$this->addPreviousButtonElement($subForm)
			->addNextButtonElement($subForm)
			->addSubFormActions($subForm)
			->setSubFormDecorators($subForm);

		$subForm->clearErrorMessages();
		return $subForm;
	}
}
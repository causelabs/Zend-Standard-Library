<?php

class HiDef_Form_Decorator_Image extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();

		if (!$element instanceof HiDef_Form_Element_File) {
			return $content;
		}
		if ($element->getView() === null) {
			return $content;
		}
		if ($element->getValue() === null) {
			return $content;
		}

		// TODO: Make sure we're actually dealing with an image
		$output = '<img class="photo" src="/media/'.$element->getValue().'"><br />';

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $ouput.$this->getSeparator().$content;
			case self::APPEND:
			default:
				return $content.$this->getSeparator().$output;
		}
	}
}
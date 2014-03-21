<?php

class HiDef_Form_Decorator_Icon extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();

		if ($element->getView() === null) {
			return $content;
		}
		if ($element->getValue() === null || $element->getValue() === '') {
			return $content;
		}

		$image = '';
		if (preg_match('([^\s]+(\.(?i)(jpg|jpeg|png|gif|bmp))$)', $element->getValue()) > 0) {
			$image = '<img src="/media/' . urlencode($element->getValue()) . '" border="0"><br>';
		}

		$output = '<div class="upload-icon"><a href="/media/' . urlencode($element->getValue()) . '">' . $image . $element->getValue().'</a><a href="#" class="remove" style="float: right;">Remove</a></div>';

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $ouput.$this->getSeparator().$content;
			case self::APPEND:
			default:
				return $content.$this->getSeparator().$output;
		}
	}
}

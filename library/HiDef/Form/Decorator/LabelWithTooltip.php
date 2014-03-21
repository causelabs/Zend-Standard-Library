<?php

class HiDef_Form_Decorator_LabelWithTooltip extends Zend_Form_Decorator_Label
{
	protected $_tooltip;

	/**
	 * Gets tooltip content
	 *
	 * @return string
	 */
	public function getTooltip()
	{
		return $this->_tooltip;
	}

	/**
	 * Sets tooltip content
	 *
	 * @param string
	 * @return HiDef_Form_Decorator_Tooltip Provides a fluent interface
	 */
	public function setTooltip($text)
	{
		$this->_tooltip = (string) $text;
		return $this;
	}

	/**
	 * Render a label
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{
		$element = $this->getElement();
		$view    = $element->getView();
		if (null === $view) {
			return $content;
		}

		$label     = $this->getLabel();
		$tooltip   = $this->getTooltip();
		$separator = $this->getSeparator();
		$placement = $this->getPlacement();
		$tag       = $this->getTag();
		$tagClass  = $this->getTagClass();
		$id        = $this->getId();
		$class     = $this->getClass();
		$options   = $this->getOptions();


		if (empty($label) && empty($tag)) {
			return $content;
		}

		if (!empty($label)) {
			$options['class'] = $class;
			$label = $view->formLabelWithTooltip($element->getFullyQualifiedName(), trim($label), trim($tooltip), $options);
		} else {
			$label = '&#160;';
		}

		if (null !== $tag) {
			require_once 'Zend/Form/Decorator/HtmlTag.php';

			$decorator = new Zend_Form_Decorator_HtmlTag();
			if (null !== $this->_tagClass) {
				$decorator->setOptions(array('tag'   => $tag,
											 'id'    => $id . '-label',
											 'class' => $tagClass));
			} else {
				$decorator->setOptions(array('tag'   => $tag,
											 'id'    => $id . '-label'));
			}


			$label = $decorator->render($label) . $tooltip->render();
		}

		switch ($placement) {
			case self::APPEND:
				return $content . $separator . $label;
			case self::PREPEND:
				return $label . $separator . $content;
		}
	}
}

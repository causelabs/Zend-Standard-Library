<?php

class HiDef_Form_Element_File extends Zend_Form_Element_File
{
	protected $_previouslyUploadedFileName;

	/**
	 * Processes the file, returns null or the filename only
	 * For the complete path, use getFileName
	 *
	 * @return null|string
	 */
	public function getValue()
	{
		if ($this->_previouslyUploadedFileName !== null) {
			return $this->_previouslyUploadedFileName;
		}

		return parent::getValue();
	}

	/**
	 * Disallow setting the value
	 *
	 * @param  mixed $value
	 * @return Zend_Form_Element_File
	 */
	public function setValue($value)
	{
		$this->_previouslyUploadedFileName = $value;
		return $this;
	}

}
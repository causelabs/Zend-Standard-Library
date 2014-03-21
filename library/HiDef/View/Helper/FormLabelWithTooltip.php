<?php
/**
 * Helper to generate a "label" element with a tooltip
 *
 * @category   HiDef
 * @package    HiDef_ZendFramework
 * @subpackage HiDef_View_Helper_FormNumber
 * @copyright Copyright (c) 2013 by HiDef, Inc.. (http://www.hidef.co)
 * @author Mark Horlbeck <mark@hidef.co>
 * @version $Id:$
  */
class HiDef_View_Helper_FormLabelWithTooltip extends Zend_View_Helper_FormElement
{
	/**
	 * Generates a 'label' element.
	 *
	 * @param  string $name The form element name for which the label is being generated
	 * @param  string $value The label text
	 * @param  string $tooltip The tooltip text
	 * @param  array $attribs Form element attributes (used to determine if disabled)
	 * @return string The element XHTML.
	 */
	public function formLabelWithTooltip($name, $value = null, $tooltip = null, array $attribs = null)
	{
		$info = $this->_getInfo($name, $value, $attribs);
		extract($info); // name, value, attribs, options, listsep, disable, escape

		// build the element
		if ($disable) {
			// disabled; display nothing
			return  '';
		}

		$value = ($escape) ? $this->view->escape($value) : $value;
		$for   = (empty($attribs['disableFor']) || !$attribs['disableFor'])
			   ? ' for="' . $this->view->escape($id) . '"'
			   : '';
		if (array_key_exists('disableFor', $attribs)) {
			unset($attribs['disableFor']);
		}

		// enabled; display label
		$xhtml = '<label'
				. $for
				. $this->_htmlAttribs($attribs)
				. '>' . $value
				. '<i class="icon-question-sign field-tooltip" data-toggle="tooltip" data-content="' . $tooltip
				. '"></i></label>';

		return $xhtml;
	}
}

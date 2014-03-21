<?php
/**
 * Helper to generate a "number" element
 *
 * @category   HiDef
 * @package    HiDef_ZendFramework
 * @subpackage HiDef_View_Helper_FormNumber
 * @copyright Copyright (c) 2011 by HiDef Web Solutions LLC. (http://www.hidefweb.com)
 * @author Mark Horlbeck <mark@hidefweb.com>
 * @version $Id:$
  */
class HiDef_View_Helper_FormNumber extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'number' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formNumber($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }

        $xhtml = '<input type="number"'
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . ' value="' . $this->view->escape($value) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;

        return $xhtml;
    }
}

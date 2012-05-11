<?php

/**
 * Supplementary view rendered
 *
 * Provides a mechanism for rendering a preferred view if it exists, otherwise
 * falling to a secondary view. Useful when you want to override a specific
 * view for a specific record in the database, similar to functionality provided
 * in other open-source CMS-type packages, e.g. WordPress.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_View
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */

/**
 * Provides a mechanism for rendering a preferred view if it exists, otherwise
 * falling to a secondary view.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_View
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_View_Helper_RenderIfExists extends Zend_View_Helper_Abstract
{
	public function init() {}

	/**
	 * Renders a preferred script in place of the fallback script if it exists;
	 * otherwise, it renders the fallback script.
	 *
	 * @param string $preferredScript Filename of preferred view script
	 * @param string $fallbackScript Filename of fallback view script
	 *
	 * @return string Rendered view script
	 */
	public function renderIfExists($preferredScript, $fallbackScript)
	{
		if ($this->_fileExists($preferredScript)) {
			return $this->view->render($preferredScript);
		}
		else {
			return $this->view->render($fallbackScript);
		}
	}

	/**
	 * Checks to see if a particular file exists within the view scripts
	 * paths
	 *
	 * @param string $file Filename of view script
	 *
	 * @return bool
	 */
	protected function _fileExists($file)
	{
		$paths = $this->view->getScriptPaths();
		foreach ($paths as $path) {
			if (file_exists($path . $file)) {
				return true;
			}
		}

		return false;
	}
}
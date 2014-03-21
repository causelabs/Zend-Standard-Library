<?php

class HiDef_Controller_Action_Helper_RenderIfExists extends Zend_Controller_Action_Helper_Abstract
{
	protected $_vr;

	public function init()
	{
		$this->_vr = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
	}

	public function direct($preferredScript, $fallbackScript)
	{
		return $this->renderIfExists($preferredScript, $fallbackScript);
	}

	public function renderIfExists($preferredScript, $fallbackScript)
	{
		if ($this->_fileExists($preferredScript)) {
			return $this->_vr->setRender($preferredScript);
		}
		else {
			return $this->_vr->setRender($fallbackScript);
		}
	}

	protected function _fileExists($file)
	{
		$normalized = $this->_vr->getViewScript($file);
		$paths = $this->_vr->view->getScriptPaths();

		foreach ($paths as $path) {
			if (file_exists($path . $normalized)) {
				return true;
			}
		}

		return false;
	}
}
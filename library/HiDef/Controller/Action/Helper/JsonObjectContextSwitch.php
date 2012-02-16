<?php
/**
 * Provides a mechanism for serializing PHP objects into full JSON objects
 *
 * PHP does not serialize its own objects via {json_encode() into
 * JSON objects. This action helper assists in providing access to all
 * properties.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller_Action
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Provides a mechanism for serializing PHP objects into full JSON objects
 *
 * PHP does not serialize its own objects via {json_encode() into
 * JSON objects. This action helper assists in providing access to all
 * properties.
 *
 * @uses 		HiDef_Model_Interface_SerializableAsJson
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller_Action
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Controller_Action_Helper_JsonObjectContextSwitch extends Zend_Controller_Action_Helper_ContextSwitch
{
	/**
	 * Controller property to utilize for context switching
	 *
	 * @var string
	 */
	protected $_contextKey = 'jsonobject';
 
	/**
	 * Constructor
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setContext($this->_contextKey, array(
			'suffix' => 'json',
			'headers' => array('Content-Type' => 'application/json'),
			'callbacks' => array(
				'post' => 'postCustomContext'
			)
		));
	}

	/**
	 * Post processing
	 *
	 * @return void
	 */
	public function postCustomContext()
	{
		if (!$this->getAutoJsonSerialization()) {
			return;
		}
 
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$view = $viewRenderer->view;
		if ($view instanceof Zend_View_Interface) {
			if (method_exists($view, 'getVars')) {
				$vars = array();
				
				foreach ($view->getVars() as $key => $var) {					
					$vars[$key] = $this->_parseVariable($var);
				}

				$this->getResponse()->setBody(Zend_Json::encode($vars));
				$viewRenderer->setNoRender(true);
			} else {
				require_once 'Zend/Controller/Action/Exception.php';
				throw new Zend_Controller_Action_Exception('View does not implement the getVars() method needed to encode the view into JSON');
			}
		}
	}

	private function _parseVariable($var)
	{
		$result = null;

		if (is_array($var))	{
			$result = array();
			foreach ($var as $v) {
				$result[] = $this->_parseVariable($v);
			}
		}
		elseif ($var instanceof HiDef_Model_Interface_SerializableAsJson) {
			$result = $var->toJson();
		}
		else {
			$result = $var;
		}

		return $result;
	}
}
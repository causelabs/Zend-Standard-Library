<?php
/**
 * HTML mailer class
 *
 * This class extends the default Zend_Mail class to provide functionality for
 * HTML email messages, using views from the /views/scripts/emails directory.
 *
 * LICENSE
 *
 * @category	HiDef_ZendStandardLibrary
 * @package     HiDef
 * @copyright   Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author      Mark Horlbeck <mark@hidef.co>
 * @version     $Id:$
 */
/**
 * HTML mailer class
 *
 * This class extends the default Zend_Mail class to provide functionality for
 * HTML email messages, using views from the /views/scripts/emails directory.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package     HiDef
 * @copyright   Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @version     $Id:$
 */
class HiDef_HtmlMailer
	extends Zend_Mail
{
	/**
	 * Current instance of the Zend_View object
	 *
	 * @var Zend_View
	 */
	static $_defaultView;

	/**
	 * Returns the default Zend_View object
	 *
	 * @static
	 * @return Zend_View
	 */
	protected static function getDefaultView()
	{
		if (self::$_defaultView === null) {
			self::$_defaultView = new Zend_View();
			self::$_defaultView->setScriptPath(APPLICATION_PATH . '/views/scripts/emails');
		}

		return self::$_defaultView;
	}

	/**
	 * Sends a specific HTML template via email
	 *
	 * @param string $template The name of the view filename to render
	 * @param int $encoding optional A Zend_Mime value
	 */
	public function sendHtmlTemplate($template, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE)
	{
		if (substr($template, -6) !== '.phtml') {
			$template .= '.phtml';
		}
		$html = $this->_view->render($template);
		$this->setBodyHtml($html, $this->getCharSet(), $encoding)->send();
	}

	/**
	 * Sets a specific view property to be rendered in the final HTML email;
	 * provides a fluent interface
	 *
	 * @param string $property Name of view property to set
	 * @param mixed $value Value of view property to set
	 * @return HiDef_HtmlMailer
	 */
	public function setViewParam($property, $value)
	{
		$this->_view->__set($property, $value);
		return $this;
	}

	/**
	 * Constructor override
	 *
	 * @param string $charset
	 * @return void
	 */
	public function __construct($charset = 'iso-8859-1')
	{
		parent::__construct($charset);
		$this->_view = self::getDefaultView();
	}
}
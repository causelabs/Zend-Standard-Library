<?php

class HiDef_Controller_Request_Cron extends Zend_Controller_Request_Simple
{
	/**
	 * Scheme for http
	 *
	 */
	const SCHEME_HTTP  = 'http';

	/**
	 * Scheme for https
	 *
	 */
	const SCHEME_HTTPS = 'https';

	public function getScheme()
	{
		return self::SCHEME_HTTP;
	}

	/**
	* Get the HTTP host as defined in the application.ini
	*
	* "Host" ":" host [ ":" port ] ; Section 3.2.2
	* Note the HTTP Host header is not the same as the URI host.
	* It includes the port while the URI host doesn't.
	*
	* @return string
	*/
	public function getHttpHost()
	{
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$config = new Zend_Config($bootstrap->getOptions());

		$scheme = $this->getScheme();
		$name   = $config->cron->mailer->server->host;
		$port   = $config->cron->mailer->server->port;

		if (null === $name) {
			return '';
		}
		elseif ($scheme == self::SCHEME_HTTP && $port == 80) {
			return $name;
		}
		else {
			return $name . ':' . $port;
		}
	}
}

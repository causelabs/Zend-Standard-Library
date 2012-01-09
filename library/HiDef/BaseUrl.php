<?php
/**
 * HiDef, Inc.
 *
 * Retrieve the current URL the web server is opperating under with provisions for command line accomidations
 *
 * @category    Zend
 * @package     HiDef
 * @author      HiDef Tech Team
 * @date        Saturday January 7, 2012
 */

class HiDef_BaseUrl
{

    const SEPARATOR         = '//';

    /**
     * The scheme of the URL (e.g. http / https )
     * @var string
     */
    protected $scheme;

    /**
     * The host portion of the URL
     * @var string
     */
    protected $host;

    /**
     * The base domain of the URL
     * @var string
     */
    protected $domain;

    /**
     * The entire url (http://www.example.com/)
     * @var string
     */
    protected $baseurl;

    /**
     * URL to fall back to if there is none defined.
     *
     * @var string
     */
    protected $default;

    /**
     * If the URL is valid
     * @var bool
     */
    protected $isValid = true;

    public function __construct($default = NULL)
    {
        $this->default = $default;

        $this->isValid();
    }

    public static function BaseUrl($default = NULL)
    {
        $object     = new self($default);
        return $object->getBaseUrl();

    }

    /**
     * Parse the current SERVER env to detect if we are on SSL or not.
     * @return string
     */
    public function getScheme()
    {
        if (NULL !== $this->scheme) {
            return $this->scheme;
        }

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $this->scheme = 'https:';
        } elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
            $this->scheme = 'https:';
        } else {
            $this->scheme = 'http:';
        }

        return $this->scheme;
    }

    /**
     * Obtain the hostname using the SERVER_NAME of the PHP $_SERVER super global. SERVER_NAME is used as that is what
     * is set in the server configs. HTTP_HOST could be manipulated by the client to forward bogas URLs.
     * http://stackoverflow.com/q/2297403/179335
     *
     * @return string
     */
    public function getHost()
    {
        if (NULL !== $this->host) {
            return $this->host;
        }

        if (!empty($_SERVER['SERVER_NAME'])) {
            $this->host = $_SERVER['SERVER_NAME'];
        }

        return $this->host;
    }

    /**
     * If the we don't have a valid url, the return the default. Otherwise assemble the baseurl and return
     *
     * @return null|string
     */
    public function getBaseUrl()
    {
        if (!$this->isValid) {
            return $this->default;
        }

        if (NULL !== $this->baseurl) {
            return $this->baseurl;
        }

        $this->baseurl = $this->getScheme() . self::SEPARATOR . $this->getHost();

        return $this->baseurl;
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $isValid
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        if (NULL === $this->getScheme() || NULL === $this->getHost()) {
            $this->isValid = false;
        } else {
            $this->isValid = true;
        }

        return $this->isValid;
    }


}
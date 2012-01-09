<?php

require(ROOT_DIR . '/library/HiDef/BaseUrl.php');

/**
 * DESCRIPTION
 *
 *
 * LICENSE:
 *
 * @category   HiDef
 * @package    HiDef_
 * @subpackage
 * @copyright  Copyright (c) 2010 HiDef Web Inc. (http://www.hidefweb.com)
 * @version    $Id:$
 */
class BaseUrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HiDef_BaseUrl
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new HiDef_BaseUrl();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

	public function testObject()
	{
		$this->assertInstanceOf('HiDef_BaseUrl', $this->object);
	}

    public function testHTTPS()
    {
        $this->assertEquals('http:', $this->object->getScheme());

        $_SERVER['HTTPS']   = 'on';
        $object = new HiDef_BaseUrl();
        $this->assertEquals('https:', $object->getScheme());

        $_SERVER['HTTPS']   = 'off';
        $object = new HiDef_BaseUrl();
        $this->assertEquals('http:', $object->getScheme());

        unset($_SERVER['HTTPS']);
        $this->assertArrayNotHasKey('HTTPS', $_SERVER);

        $_SERVER['SERVER_PORT'] = 80;
        $object = new HiDef_BaseUrl();
        $this->assertEquals('http:', $object->getScheme());

        $_SERVER['SERVER_PORT'] = 443;
        $object = new HiDef_BaseUrl();
        $this->assertEquals('https:', $object->getScheme());

    }

    public function testDefaultUrl()
    {
        $default = 'http://www.example.com';
        $object = new HiDef_BaseUrl($default);
        $this->assertEquals($default, $object->getDefault());

    }

    public function testValidUrl()
    {
        $name = $_SERVER['SERVER_NAME'] = 'www.example.com';
        $port = $_SERVER['SERVER_PORT'] = 443;

        $object = new HiDef_BaseUrl('http://www.ADifferentURL.com');
        $this->assertEquals('https://' . $name, $object->getBaseUrl());
    }

    public function testStaticBaseUrl()
    {
        $name = $_SERVER['SERVER_NAME'] = 'www.example.com';
        $port = $_SERVER['SERVER_PORT'] = 443;

        $this->assertEquals('https://' . $name, HiDef_BaseUrl::BaseUrl());
    }


}
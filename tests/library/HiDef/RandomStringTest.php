<?php

require(ROOT_DIR . '/library/HiDef/RandomString.php');

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
class RandomStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HiDef_RandomString
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new HiDef_RandomString();
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
		$this->assertInstanceOf('HiDef_RandomString', $this->object);
	}

    public function testDefaultRandomStringLength()
    {
        $this->assertEquals(10, strlen($this->object->getRandomString()));

    }

    public function testCustomRandomStringLength()
    {
        $length = 15;
        $this->assertEquals($length, strlen($this->object->setLength($length)->getRandomString()));
    }

    public function testSetPool()
    {
        $pool = 'abc';
        $random_string = $this->object->setPool($pool)->getRandomString();
        $array = str_split($random_string);
        foreach ($array as $char) {
            if (0 === preg_match('/[' . $pool . ']/', $char)) {
                $this->fail();
            }
        }
    }

    public function testStaticGetWithDefaultLength()
    {
        $this->assertEquals(10, strlen(HiDef_RandomString::Get()));
    }

    public function testStaticGetWithCustomLength()
    {
        $this->assertEquals(15, strlen(HiDef_RandomString::Get(15)));
    }

}
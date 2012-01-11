<?php

require(ROOT_DIR . '/library/HiDef/View/Helper/Truncate.php');

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
class TruncateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HiDef_View_Helper_Truncate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new HiDef_View_Helper_Truncate();
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
		$this->assertInstanceOf('HiDef_View_Helper_Truncate', $this->object);
	}

    public function testDefaults()
    {

        $this->assertRegExp('/\.\.\.$/', $this->object->truncate($this->getString()));
        $this->assertEquals(103, strlen($this->object->truncate($this->getString())));
    }

    public function testCustomPostfix()
    {

        $this->assertRegExp('/333$/', $this->object->truncate($this->getString(), 100, '333'));

    }

    public function testNoPostfixForShortString()
    {
        $this->assertNotRegExp('/\.\.\.$/', $this->object->truncate('A short string'));
    }

    /**
     * Testing to make sure 'A short string' does not end up as 'A short st...", instead it will be 'A short...';
     */
    public function testDoesNotChopWord()
    {
        $short = 'A short string';
        $truncated = $this->object->truncate($short, 10);

        $this->assertEquals(10, strlen($truncated));
    }

    protected function getString()
    {
        return 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore';
    }


}
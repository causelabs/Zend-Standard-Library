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
     * @var Zend_View_Helper_Truncate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Zend_View_Helper_Truncate();
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
		$this->assertInstanceOf('Zend_View_Helper_Truncate', $this->object);
	}


}
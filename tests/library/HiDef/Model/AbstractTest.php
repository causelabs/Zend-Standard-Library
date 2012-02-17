<?php

require(ROOT_DIR . '/library/HiDef/Model/Abstract.php');
require(ROOT_DIR . '/library/HiDef/Model/Exception/InvalidProperty.php');

class AbstractTest extends PHPUnit_Framework_TestCase
{
	protected $_object;

	public function setUp()
	{
		$this->_object = new HiDef_Model_Abstract();
	}

	public function testCanCreateObject()
	{
		$this->assertInstanceOf('HiDef_Model_Abstract', $this->_object);
	}

	/**
	 * @expectedException HiDef_Model_Exception_InvalidProperty
	 */
	public function testInvalidMutator()
	{
		$this->_object->invalidProperty = 'Throws an exception';
	}

	/**
	 * @expectedException HiDef_Model_Exception_InvalidProperty
	 */
	public function testInvalidAccessor()
	{
		$this->assertNull($this->_object->invalidProperty);
	}

	public function testSetOptions()
	{
		$obj = new HiDef_Model_Abstract();
		$properties = array(
			'id' => 0,
			'_id' => 5,
			'nonExistant' => 'property',
		);

		$obj->setOptions($properties);
	}

	public function testConversionToArrayIsEmpty()
	{
		$obj = new HiDef_Model_Abstract();

		$array = $obj->toArray();

		$this->assertEquals(0, count($array));
	}
}

<?php
/**
 * Abstract model class
 *
 * Base class on which to base model classes. Utilizes several helper methods
 * to make setting properties consistent, along with magic methods when no
 * getters or setters are present.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @subpackage	Model
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Abstract model class
 *
 * Base class on which to base model classes. Utilizes several helper methods
 * to make setting properties consistent, along with magic methods when no
 * getters or setters are present.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @subpackage	Model
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Model_Abstract
{
	/**
	 * Generic constructor
	 * - Sets class properties based on passed in array
	 * 
	 * @param array $properties Associative array mapping of property names => values
	 */
	public function __construct(array $properties=array())
	{
		$this->setOptions($properties);
	}

	/**
	 * Generic mutator
	 *
	 * @throws HiDef_Model_Exception_InvalidProperty If attempting to set an invalid property
	 * @param string $name Property name to set
	 * @param string $value Value to set property to
	 * @return HiDef_Model_Abstract Provides a fluent interface
	 */
	public function __set($name, $value)
	{
		// See if an explicit mutator exists
		$method = 'set' . ucfirst($name);
		if (!method_exists($this, $method)) {
			// Just set the property, if it exists
			$property = $this->_getFormattedPropertyName($name);
			if (property_exists($this, $property)) {
				$this->$property = $value;
				return $this;
			}
			else {
				throw new \HiDef_Model_Exception_InvalidProperty($property, get_class($this));
			}
		}
		else {
			$this->$method($value);
		}

		return $this;
	}

	/**
	 * Generic accessor
	 *
	 * @throws HiDef_Model_Exception_InvalidProperty If attempting to get an invalid property
	 * @param string $name Property name to retrieve
	 * @return mixed
	 */
	public function __get($name)
	{
		// See if an explicit accessor exists
		$method = 'get' . ucfirst($name);
		if (!method_exists($this, $method)) {
			// Just get the property, if it exists
			$property = $this->_getFormattedPropertyName($name);
			if (property_exists($this, $property)) {
				return $this->$property;
			}
			else {
				throw new \HiDef_Model_Exception_InvalidProperty($property, get_class($this));
			}
		}
		else {
			return $this->$method();
		}
	}

	/**
	 * Sets all properties of an object based on a given array mapping
	 *
	 * @param array $options Associative array mapping of property names => values
	 * @return HiDef_Model_Abstract Provides a fluent interface
	 */
	public function setOptions(array $options)
	{
		$properties = get_class_vars(get_class($this));
		$methods = get_class_methods($this);

		foreach ($options as $key => $value) {
			// Use a custom mutator, if available
			$method = 'set' . ucfirst($key);

			if (in_array($method, $methods)) {
				$this->$method($value);
			}
			// Use the standard magic method mutator
			elseif (in_array('__set', $methods)) {
				if (array_key_exists($key, $properties)) {
					$this->$key = $value;
				}
				// Prefixed property name
				elseif (array_key_exists($this->_getFormattedPropertyName($key), $properties)) {
					$formattedKey = $this->_getFormattedPropertyName($key);
					$this->$formattedKey = $value;
				}
			}
		}

		return $this;
	}

	/**
	 * Serializes all properties of the class to an array. Will utilize custom
	 * accessors and mutators if they exist
	 * 
	 * @return array
	 */
	public function toArray()
	{
		$properties = get_class_vars(get_class($this));
		$methods = get_class_methods($this);
		$results = array();

		foreach ($properties as $property => $defaultValue) {
			if (substr($property, 0, 1) === '_') {
				$property = substr($property, 1); // Remove the leading underscore
			}

			$method = 'get' . ucfirst($property);

			// Use a custom accessor, if available
			if (in_array($method, $methods)) {
				$val = $this->$method();
				$results[$property] = $val;
			}
			// Use the standard magic method mutator
			elseif (in_array('__get', $methods)) {
				$results[$property] = $this->$property;
			}
		}

		return $results;
	}

	/**
	 * Returns the name of the member variable representing the given property
	 * 
	 * @param  string $name Name of property for which to map property name
	 * @return string
	 */
	private function _getFormattedPropertyName($name)
	{
		return '_' . $name;
	}
}

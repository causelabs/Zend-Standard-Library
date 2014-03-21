<?php

abstract class HiDef_Model_Mapper_Abstract
{
	protected $_modelClass;
	protected $_dbTableClass;
	protected $_dbTable;

	public function __construct($tableClass, $modelClass = null) {
		$this->_dbTableClass = $tableClass;
		$this->_modelClass = $modelClass;
	}

	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable($this->_dbTableClass);
		}
		return $this->_dbTable;
	}

	/**
	 * Retrieves records from the database
	 *
	 * @param string $field Optional name of field on which to base record retrieval; if null, all records are retrieved
	 * @param string $value Optional value of field on which to base record retrieval; if null, $field is used as the value and "id" is set as the field
	 * @param string $order Optional name of field on which to order record retrieval
	 * @return array Array of HiDef_Model_Abstract descendent
	 */
	public function get($field = null, $value = null, $order = null)
	{
		// Set default field
		if ($field === null) {
			$rowset = $this->getDbTable()->fetchAll(null, $order);
		}
		else {
			if ($value === null) {
				$value = $field;
				$field = 'id';
			}

			$select = $this->getDbTable()->select()->where('`'.$field.'` = ?', $value);

			if ($order !== null) {
				$select->order($order);
			}

			$rowset = $this->getDbTable()->fetchAll($select);
		}

		$results = $this->_buildObjects($rowset);

		return $results;
	}

	/**
	 * Retrieves a single record from the database
	 *
	 * @param string $field Optional name of field on which to base record retrieval; if null, all records are retrieved
	 * @param string $value Optional value of field on which to base record retrieval; if null, $field is used as the value and "id" is set as the field
	 * @param string $order Optional name of field on which to order record retrieval
	 * @return HiDef_Model_Abstract HiDef_Model_Abstract descendent
	 */
	public function getOne($field = null, $value = null, $order = null)
	{
		$results = $this->get($field, $value, $order);

		if (is_array($results) && isset($results[0])) {
			return $results[0];
		}
		else {
			return null;
		}
	}

	/**
	 * Saves records to the database
	 *
	 * @param array $data Array of key => value pairs, where "key" is the field name to save and "value" is the content of the field
	 * @param int $id Integer identifier of the record, to determine between INSERT and UPDATE operations
	 * @param string $idField Name of ID field if not "id"
	 * @return int Returns the integer ID of the inserted/updated database record
	 */
	public function save($data, $id = null, $idField = 'id') {
		if ($data instanceof HiDef_Model_Abstract) {
			$data = $data->toArray();
		}

		$_id = 0;

		if (null === ($_id = $id)) {
			unset($data['id']);

			$_id = $this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('`'.$idField . '` = ?' => $id));
		}

		return $_id;
	}

	/**
	 * Deletes from the database, by default, a record identified by ID, but optionally any set of records identified by a single column value
	 *
	 * @param mixed $field If an integer, the ID number of the record to delete; if a string, the field name of the identification column to use
	 * @param mixed $value The value to match in the identification column
	 */
	public function delete($field, $value = null)
	{
		// Set default field
		if ($value === null) {
			$value = $field;
			$field = 'id';
		}

		$this->getDbTable()->delete(array('`'.$field.'` = ?' => $value));
	}

	/**
	 * Deletes all records from a table
	 */
	public function deleteAll()
	{
		$this->getDbTable()->delete();
	}

	protected function _buildObjects(Zend_Db_Table_Rowset_Abstract $rowset, $recursive = true)
	{
		$results = array();

		foreach ($rowset as $row) {			
			$results[] = $this->_buildObject($row, $recursive);
		}

		return $results;
	}

	protected function _buildObject(Zend_Db_Table_Row $row, $recursive = true)
	{
		$modelClass = $this->_modelClass;
		return new $modelClass($row);
	}
}
?>
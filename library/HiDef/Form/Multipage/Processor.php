<?php

class HiDef_Form_Multipage_Processor
{
	protected $_form;
	protected $_session;
	protected $_finalData;

	public function __construct(HiDef_Form_Multipage $form, Zend_Session_Namespace $namespace=null)
	{
		$this->setForm($form);

		if ($namespace === null) {
			$this->setSessionNamespace(new Zend_Session_Namespace($this->getNamespaceId()));
		}
		else {
			$this->setSessionNamespace($namespace);
		}
	}

	public function getNamespaceId()
	{
		return __CLASS__ . get_class($this->getForm());
	}

	public function setSessionNamespace(Zend_Session_Namespace $namespace=null)
	{
		$this->_session = $namespace;
		return $this;
	}

	public function getSessionNamespace()
	{
		if (null === $this->_session) {
			$this->_session = new Zend_Session_Namespace($this->getNamespaceId());
		}

		return $this->_session;
	}

	public function clearSessionNamespace()
	{
		$this->getSessionNamespace()
			->unsetAll();
		$this->setSessionNamespace(null);
	}

	public function getSessionNamespaceData()
	{
		$data = array();
		foreach ($this->getSessionNamespace() as $key => $info) {
			$data[$key] = $info[$key];
		}

		return $data;
	}

	public function setForm(HiDef_Form_Multipage $form)
	{
		$this->_form = $form;
		return $this;
	}

	public function getForm()
	{
		return $this->_form;
	}

	public function getStoredPages()
	{
		$results = array();
		foreach ($this->getSessionNamespace() as $key => $value) {
			$results[] = $key;
		}

		return $results;
	}

	public function getPages()
	{
		return array_keys($this->getForm()->getSubForms());
	}

	public function getCurrentPage(Zend_Controller_Request_Abstract $request)
	{
		foreach ($this->getPages() as $name) {
			if ($data = $request->getPost($name, false)) {
				if (is_array($data)) {
					return $this->getForm()->getSubForm($name);
					break;
				}
			}
		}

		return false;
	}

	public function getNextPage()
	{
		$stored = $this->getStoredPages();
		$potential = $this->getPages();

		foreach ($potential as $name) {
			if (!in_array($name, $stored)) {
				$form = $this->getForm()->getSubForm($name);
				return $this->getForm()->prepareSubForm($form);
			}
		}

		return false;
	}

	public function subFormIsValid(Zend_Form_SubForm $subForm, array $data)
	{
		$name = $subForm->getName();
		if ($subForm->isValid($data)) {
			$this->getSessionNamespace()->$name = $subForm->getValues();
			return true;
		}

		return false;
	}

	public function formIsValid()
	{
		$data = $this->getSessionNamespaceData();

		// We have to clone the form so that we don't set errors on subforms
		// that haven't been displayed yet.
		$form = clone $this->getForm();
		return $form->isValid($data);
	}

	public function setFinalData()
	{
		$this->_finalData = $this->getSessionNamespaceData();
		return $this;
	}
	public function getFinalData()
	{
		return $this->getSessionNamespaceData();
	}

	public function isComplete()
	{
		return $this->formIsValid();
	}

	public function reset()
	{
		$this->clearSessionNamespace();
	}

	public function process(Zend_Controller_Request_Abstract $request)
	{
		// If we're arriving because of a non-POST request, give the first
		// page.
		if (!$request->isPost() || !$form = $this->getCurrentPage($request)) {
			return $this->getNextPage();
		}

		// Is the submitted subform valid? If not, return it to be filled out
		// some more
		if (!$this->subFormIsValid($form, $request->getPost())) {
			return $this->getForm()->prepareSubForm($form);
		}

		// If the entire form is not valid, retrieve the next subform
		if (!$this->formIsValid()) {
			$form = $this->getNextPage();
			return $this->getForm()->prepareSubForm($form);
		}
		else {
			$this->setFinalData();
		}
	}
}
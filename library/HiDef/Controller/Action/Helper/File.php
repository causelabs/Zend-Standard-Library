<?php

class Crowdseed_Controller_Action_Helper_File extends Zend_Controller_Action_Helper_Abstract
{
	public function init() {}
	
	public function process($file)
	{
		if ($file->isUploaded()) {
			// Get metadata on file
			$filepath = $file->getFileName();
			$info = pathinfo($filepath);
			
			// Rename file according to its content digest
			$hash = sha1_file($filepath);
			rename($filepath, dirname($filepath).DIRECTORY_SEPARATOR.$hash.'.'.$info['extension']);
			
			return $hash.'.'.$info['extension'];
		}
		else {
			return null;
		}
	}
	
	public function delete($file)
	{
		// Get configuration to discover destination path
		$config = Zend_Registry::get('config');
		$uploadPath = $config->resources->view->params->userProfileUploadPath;
		
		@unlink($uploadPath.DIRECTORY_SEPARATOR.$file);
	}
	
}
?>
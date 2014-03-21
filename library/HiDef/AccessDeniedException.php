<?php

class HiDef_AccessDeniedException extends Exception
{
	public function __construct()
	{
		$this->message = 'Access is denied';
	}
}
?>
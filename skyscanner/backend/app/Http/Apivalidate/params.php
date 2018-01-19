<?php

class params
{
	private $_container;
	
	public function __construct()
	{
		$this->_container = null;
	}
	
	public function set()
	{
		if (isset($_GET)) {
			$this->_container = $_GET;
		} else if (isset($_POST)) {
			$this->_container = $_POST;
		}
	}

	public function get()
	{
		return 	$this->_container;
	}

	function _get($str)
	{
		$val = !empty($this->_container[$str]) ? $this->_container[$str] : null;
		return $val;
	}

}
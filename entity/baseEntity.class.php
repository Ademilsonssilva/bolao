<?php
	
namespace Ademilson\Bolao\Entity;

class BaseEntity
{
	public $conn;

	function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

}
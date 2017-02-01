<?php
class Base_Model extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	public function write($text)
	{
		echo $text;
	}
}

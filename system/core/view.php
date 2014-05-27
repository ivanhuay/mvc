<?php
class view{
	function __construct()
	{
		echo "<p>Esta es la vista.</p>";
	}
	public function load($name)
	{
		require('application/view/'.$name.'php');
	}
}
<?php

class Base extends Controller{

	function __construct()
	{
		parent::__construct();
		echo "inde controller";
	}
	public function otro($parametro=FALSE)
	{
		echo "otro estamos en otro";

		if($parametro)
		{
			echo "<br> Parametro: $parametro";
		}
	}
}
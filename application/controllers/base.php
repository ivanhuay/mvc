<?php

class Base extends Controller{

	function __construct()
	{
		parent::__construct();
		echo "base controller";
	}
	public function otro($parametro=FALSE)
	{
		echo "otro estamos en otro";
		$data['titulo']='Nuevo dia aprendiendo cosas';
		if($parametro)
		{
			echo "<br> Parametro: $parametro";
		}
		$this->load->model('base_model');
		$this->load->view('prueba',$data);

		$this->base_model->write("<p>probando si funciona</p>");

	}
}
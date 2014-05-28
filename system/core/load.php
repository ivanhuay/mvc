<?php
class Load
{
	function __construct()
	{

	}

	public function view($view,$data=array()){
		extract($data);
		require(APPFOLDER.'views/'.$view.'.php');
	}
	public function model($model)
	{
		require(APPFOLDER.'models/'.$model.'.php');
		$Ctr= get_instance();
		$Ctr->{$model}=new $model;
	}
}
<?php
class Load
{
	function __construct()
	{

	}

	public function view($view,$data=array()){

		if (is_array($view))
		{
			foreach ($view as $babe)
			{
				$this->view($babe);
			}
			return;
		}
		//transforma el array asociativo en variables para la vista
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
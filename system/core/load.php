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
		
		$_file=APPFOLDER.'views/'.$view.'.php';
		if(file_exists($_file))
		{
			//transforma el array asociativo en variables para la vista
			extract($data);
			require($_file);
		}
	}
	public function model($model)
	{
		$_file=APPFOLDER.'models/'.$model.'.php';
		if(file_exists($_file))
		{
			require($_file);
			$Ctr= get_instance();
			$Ctr->{$model}=new $model;
		}
	}
	public function helper($helper)
	{
		$_file=APPFOLDER.'helper/'.$helper.'.php';
		if(file_exists($_file))
		{
			require_once($_file);
		}
	}
}
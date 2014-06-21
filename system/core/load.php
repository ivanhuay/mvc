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
		
		$file=APPFOLDER.'views/'.$view.'.php';
		if(file_exists($file))
		{
			//transforma el array asociativo en variables para la vista
			extract($data);
			require($file);
		}
	}
	public function model($model)
	{
		$file=APPFOLDER.'models/'.$model.'.php';
		if(file_exists($file))
		{
			require($file);
			$Ctr= get_instance();
			$Ctr->{$model}=new $model;
		}
	}
	public function helper($helper)
	{
		$file=APPFOLDER.'helper/'.$helper.'.php';
		if(file_exists($file))
		{
			require_once($file);
		}
	}
}
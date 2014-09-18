<?php
class Load
{
	function __construct()
	{
		
	}

	public function view($_view,$_data=array()){

		if (is_array($_view))
		{
			foreach ($_view as $babe)
			{
				$this->view($babe,$data);
			}
			return;
		}
		
		$_file=APPFOLDER.'views/'.$_view.'.php';
		if(file_exists($_file))
		{
			//transforma el array asociativo en variables para la vista
			extract($_data);
			require($_file);
		}
	}
	public function model($_model)
	{
		$_file=APPFOLDER.'models/'.$_model.'.php';
		if(file_exists($_file))
		{
			require($_file);
			$Ctr= get_instance();
			$Ctr->{$model}=new $model;
		}
	}
	public function helper($_helper)
	{
		$_file=APPFOLDER.'helper/'.$_helper.'.php';
		if(file_exists($_file))
		{
			require_once($_file);
		}
	}
	public function template($_template,$_view,$_data=array())
	{
		$_file=APPFOLDER.'views/templates/'.$_template.'.php';
		if(file_exists($_file))
		{
			require_once($_file);
			for($i=0;$i<count($template);$i++)
			{
				if($template[$i]!="view"){
					$this->view($template[$i],$_data);
				}else{
					
					if (is_array($_view))
					{
						foreach ($_view as $babe)
						{
							$this->view($babe,$data);
						}
						return;
					}
					$this->view($_view,$_data);
				}
			}
		}
	}
}
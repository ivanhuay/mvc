<?php

class Plank{
	function __construct()
	{
		$url=(isset($_GET['url']))?$_GET['url']:NULL;
		$url=rtrim($url,'/');
		$url=explode('/',$url);
		require(APPFOLDER."config/routes.php");
		require(APPFOLDER."config/only_index_controller.php");

		if(empty($url[0]))
		{
			$defapp=$config['routes']['default_controller'];
			$file_def=APPFOLDER.'controllers/'.$defapp.'.php';
			
			if($defapp!='' && file_exists($file_def))
			{
				require($file_def);
				$controller = new $defapp;
			}else
			{
				require(APPFOLDER.'controllers/index.php');
				$controller=new Index();
			}
			$controller->index();
			return FALSE;
		}

		$file='application/controllers/'.$url[0].'.php';
		if(file_exists($file))
		{
			require($file);	
		}else
		{
			require(SYSTEM.'core/error.php');
			$controller=new Error();
			return FALSE;
		}
		
		$controller= new $url[0];
		if($this->serachInArray($config["only_index"],$url[0])){
			if(isset($url[3])){
			
				$controller->index($url[1],$url[2],$url[3]);
					
			}else if(isset($url[2]))
			{
				
				$controller->index($url[1],$url[2]);

				return FALSE;
				
			}else if (isset($url[1]))
			{
				$controller->index($url[1]);
				
				return FALSE;
			}else
			{
				$controller->index();
			}
		}else{

			if(isset($url[3])){
				if(method_exists($controller,$url[1]))
				{
					$controller->{$url[1]}($url[2],$url[3]);
				}	
			}else if(isset($url[2]))
			{
				if(method_exists($controller,$url[1]))
				{
					$controller->{$url[1]}($url[2]);
				}else
				{
					echo "errr";
				}
				
				return FALSE;
			}else if (isset($url[1]))
			{
				$controller->{$url[1]}();
				return FALSE;
			}else
			{
				$controller->index();
			}
		}
	}
	function serachInArray($array = array(), $search = FALSE){
		if($search){
			for($i = 0; $i< count($array);$i++){
				if($search == $array[$i])return TRUE;
			}
		}
		return FALSE;
	}
}
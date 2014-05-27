<?

class Controller{
	function __construct()
	{
		echo "Main Controller";
		$this->view=new View();
	}
}
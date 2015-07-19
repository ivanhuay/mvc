<?
class Template extends Controller
{
	function __construct(){
		parent::__construct();
	}
	function index(){
		$this->load->template("example","index/example_template");
	}
}
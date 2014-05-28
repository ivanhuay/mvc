<?

class Controller{

	private static $instance;

	function __construct()
	{
		self::$instance =& $this;

		echo "Main Controller<br>";
		$this->load=new Load();
	}
	public static function get_instance()
	{
		return self::$instance;
	}
}
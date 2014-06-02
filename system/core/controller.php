<?

class Controller{

	private static $instance;

	function __construct()
	{
		self::$instance =& $this;

		
		$this->load=new Load();
	}
	public static function get_instance()
	{
		return self::$instance;
	}
}
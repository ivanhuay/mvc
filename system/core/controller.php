<?php
class Controller extends BaseClass
{
    private static $instance;

    public function __construct()
    {
        parent::__construct();
        self::$instance = &$this;

        $this->load = new Load();
        $this->session = new Session();
        require APPFOLDER.'config/autoload.php';
        $models = $config['autoload']['models'];
        for ($i = 0;$i < count($models);++$i) {
            $this->load->model($models[$i]);
        }
    }
    public static function get_instance()
    {
        return self::$instance;
    }
}

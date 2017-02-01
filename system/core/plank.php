<?php

require APPFOLDER.'config/routes.php';
require APPFOLDER.'config/only_index_controller.php';

class Plank extends BaseClass
{
    public function __construct()
    {
        $url = $this->urlManage();

        if (empty($url[0])) {
            $defapp = $config['routes']['default_controller'];
            $file_def = APPFOLDER.'controllers/'.$defapp.'.php';

            if ($defapp != '' && file_exists($file_def)) {
                require $file_def;
                $controller = new $defapp();
            } else {
                require APPFOLDER.'controllers/index.php';
                $controller = new Index();
            }
            $controller->index();

            return false;
        }

        if ($url[0] == 'api') {
            return $this->handleCollection($url);
        }

        $file = 'application/controllers/'.$url[0].'.php';
        if (file_exists($file)) {
            require $file;
        } else {
            require SYSTEM.'core/error.php';
            $controller = new Error();

            return false;
        }

        $controller = new $url[0]();
        if ($this->serachInArray($config['only_index'], $url[0])) {
            if (isset($url[3])) {
                $controller->index($url[1], $url[2], $url[3]);
            } elseif (isset($url[2])) {
                $controller->index($url[1], $url[2]);

                return false;
            } elseif (isset($url[1])) {
                $controller->index($url[1]);

                return false;
            } else {
                $controller->index();
            }
        } else {
            if (isset($url[3])) {
                if (method_exists($controller, $url[1])) {
                    $controller->{$url[1]}($url[2], $url[3]);
                }
            } elseif (isset($url[2])) {
                if (method_exists($controller, $url[1])) {
                    $controller->{$url[1]}($url[2]);
                } else {
                    echo 'errr';
                }

                return false;
            } elseif (isset($url[1])) {
                $controller->{$url[1]}();

                return false;
            } else {
                $controller->index();
            }
        }
    }
    public function serachInArray($array = array(), $search = false)
    {
        if ($search) {
            for ($i = 0; $i < count($array);++$i) {
                if ($search == $array[$i]) {
                    return true;
                }
            }
        }

        return false;
    }
    //manage url to get controllers
    public function urlManage()
    {
        $fullurl = $_SERVER['PHP_SELF'];
        if (!$fullurl || !isset($fullurl) || empty($fullurl)) {
            return array();
        }
        $parseUrl = explode('index.php', $fullurl);
        if (count($parseUrl) < 2) {
            return array();
        }
        $url = trim($parseUrl[1], '/');
        $url = explode('/', $url);

        return $url;
    }

    private function handleCollection($url = [])
    {
        if ($url[0] != 'api') {
            $this->logger->error("first url path isn't api path on handleCollection");

            return false;
        }
        $file = $this->collectionFile($url[1]);
        if (!$file) {
            $this->logger->error('no collection found');
        }

        require $file;
        $collection = new $url[1]();
        $collection->handleRequest();
    }

    private function collectionFile($collection)
    {
        $file = 'application/collections/'.$collection.'.php';

        return file_exists($file) ? $file : false;
    }
}

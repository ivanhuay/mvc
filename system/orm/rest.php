<?php

require APPFOLDER.'config/collections.php';

class Rest extends BaseClass
{
    public function __construct($tableName = 'default', $enabledMethod = ['GET', 'POST', 'PUT', 'DELETE'])
    {
        parent::__construct();
        global $config;
        $this->config = $config['collections'];
        $this->tableName = $tableName;
        $this->enabledMethod = $enabledMethod;
        $this->db = new ORM();
    }

    public function __call($method, $arguments)
    {
        if (in_array($_SERVER['REQUEST_METHOD'], $this->enabledMethod) || in_array($this->getToken(), $this->config['fullAccessToken'])) {
            $this->logger->info('Authorized - method:'.$method);

            return call_user_func_array(array($this, $method), $arguments);
        } else {
            $this->logger->info('Unauthorized access - method:'.$method.' request:'.$_SERVER['REQUEST_METHOD']);

            return $this->respJson(['message' => 'Unauthorized'], 401);
        }
    }

    private function getToken()
    {
        $HEADERS = getallheaders();
        if (array_key_exists('token', $_GET)) {
            return $_GET['token'];
        } elseif (array_key_exists('token', $HEADERS)) {
            return $HEADERS['token'];
        } else {
            return false;
        }
    }
    protected function post()
    {
        $queryValues = '';
        $queryColumns = '';
        foreach ($_POST as $key => $value) {
            $queryColumns .= $this->db->real_escape_string($key).',';
            $queryValues .= "'".$this->db->real_escape_string($value)."',";
        }
        $queryColumns = trim($queryColumns, ',');
        $queryValues = trim($queryValues, ',');
        $insert = $this->db->new_query('INSERT INTO '.$this->db->real_escape_string($this->tableName).' ('.$queryColumns.') VALUES ('.$queryValues.')');
        if ($this->db->affected_rows == 1) {
            $itemResp = $this->db->new_query('SELECT * FROM '.$this->db->real_escape_string($this->tableName)." WHERE _id='".$this->db->insert_id."'");
            $this->respJson(['success' => true, 'data' => $itemResp->fetch_assoc()]);
        } else {
            $this->respJson(['error' => true, 'message' => 'Could not create record']);
        }
    }

    protected function get($pathData)
    {
        if (count($pathData) > 0 && $pathData[0] != 'page') {
            $id = $this->db->real_escape_string($pathData[0]);
            $queryAll = $this->db->new_query('SELECT * FROM '.$this->db->real_escape_string($this->tableName)." WHERE _id='".$id."'");
            $allResp = $this->db->fetch_all($queryAll);
            $this->respJson(['success' => true, 'data' => $allResp]);
        } else {
            $queryAll = $this->db->new_query('SELECT * FROM '.$this->db->real_escape_string($this->tableName));
            $allResp = $this->db->fetch_all($queryAll);
            $this->respJson(['success' => true, 'data' => $allResp]);
        }
    }

    protected function put($pathData)
    {
        if (count($pathData) > 0) {
            $id = $this->db->real_escape_string($pathData[0]);
            $queryValues = '';
            $_PUT = [];

            parse_str(file_get_contents('php://input'), $_PUT);
            foreach ($_PUT as $key => $value) {
                $queryValues .= $this->db->real_escape_string($key).'='."'".$this->db->real_escape_string($value)."',";
            }
            $queryValues = trim($queryValues, ',');
            $updateResp = $this->db->new_query('UPDATE '.$this->db->real_escape_string($this->tableName).' SET '.$queryValues." WHERE _id='".$id."'");
            if ($updateResp) {
                $itemResp = $this->db->new_query('SELECT * FROM '.$this->db->real_escape_string($this->tableName)." WHERE _id='".$id."'");
                $this->respJson(['success' => true, 'data' => $itemResp->fetch_assoc()]);
            } else {
                $itemResp = $this->db->new_query('SELECT * FROM '.$this->db->real_escape_string($this->tableName)." WHERE _id='".$id."'");

                $this->respJson(['error' => true, 'message' => 'Could not update record'], 400);
            }
        } else {
            $this->respJson(['error' => true, 'message' => 'missing parameter /(:id) required'], 400);
        }
    }

    protected function delete($pathData)
    {
        if (count($pathData) > 0) {
            $id = $this->db->real_escape_string($pathData[0]);
            $this->db->new_query('DELETE FROM  '.$this->db->real_escape_string($this->tableName)." WHERE _id='".$id."'");

            if ($this->db->affected_rows >= 1) {
                $this->respJson(['success' => true, 'message' => 'Document Deleted.']);
            } else {
                $this->respJson(['error' => true, 'message' => 'Could not delete record'], 400);
            }
        } else {
            $this->respJson(['error' => true, 'message' => 'missing parameter /(:id) required'], 400);
        }
    }

    public function respJson($arrayObject = [], $statusCode = 200)
    {
        header('HTTP/1.1 '.$statusCode);
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($arrayObject);
        exit;
    }

    protected function handleRequest($pathData = [])
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        return $this->{$method}($pathData);
    }
}

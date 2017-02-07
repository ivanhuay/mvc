<?php

class Collection extends Rest
{
    public function __construct($enabledMethod = ['GET', 'POST', 'PUT', 'DELETE'], $collectionName = null)
    {
        if (empty($collectionName)) {
            $collectionName = get_called_class();
        }
        parent::__construct(strtolower($collectionName), $enabledMethod);
        $this->collectionName = strtolower($collectionName);
        $this->structure = [];
        $this->validation = [];
    }

    private function createTable($collectionName, $structure)
    {
        $tableEstructure = '';
        if (!array_key_exists('_id', $structure)) {
            $this->structure['_id'] = 'int auto_increment primary key';
        }

        foreach ($this->structure as $key => $value) {
            $tableEstructure .= $key.' '.$value.',';
        }

        $collectionName = $this->db->real_escape_string($collectionName);
        $tableEstructure = $this->db->real_escape_string(trim($tableEstructure, ','));

        $this->db->new_query('CREATE TABLE '.$collectionName.' ('.$tableEstructure.')');
    }

    private function backupTable($tableName = null)
    {
        $tableName = $this->db->real_escape_string($tableName);

        if (empty($tableName)) {
            $this->logger->error('empty tableName backupTable');

            return false;
        }

        $tablesResult = $this->db->new_query('SHOW TABLES FROM '.DB_NAME.' WHERE Tables_in_'.DB_NAME." like '".$tableName."%'");
        $count_tables = $tablesResult->num_rows;

        $this->logger->info('Table Count: '.$count_tables);

        if ($count_tables == 0) {
            $this->createTable($this->collectionName, $this->structure);
        } else {
            $tableStructure = $this->db->new_query('describe '.$tableName);
            $duplicatedTable = false;

            while ($row = $tableStructure->fetch_assoc()) {
                if (array_key_exists($row['Field'], $this->structure)) {
                    $dbType = strtolower(preg_replace("/\([^)]+\)/", '', $row['Type']));
                    $collectionFieldDetail = strtolower($this->structure[$row['Field']]);
                    $this->logger->info('Field: '.$row['Field'].' -> '.$collectionFieldDetail.', type: '.$dbType);

                    if (
                      (strpos($collectionFieldDetail, $dbType) === false) ||
                      (strpos($collectionFieldDetail, 'key') === false && !empty($row['Key']))
                    ) {
                        $this->logger->info('collectionField '.$row['Field']." change type from: $dbType, to: $collectionFieldDetail");
                        if (!$duplicatedTable) {
                            $this->db->duplicteTable($this->collectionName,  $this->collectionName.'_pbackup_'.$count_tables);
                            $duplicatedTable = true;
                        }
                        $this->db->alterColumn($this->collectionName, $row['Field'], $collectionFieldDetail);
                    }
                } elseif ($row['Field'] != '_id') {
                    $this->logger->info('field not exist '.$row['Field']);

                    if (!$duplicatedTable) {
                        $this->db->duplicteTable($this->collectionName, $this->collectionName.'_pbackup_'.$count_tables);
                        $duplicatedTable = true;
                    }
                    $this->db->addColumn($this->collectionName, $row['Field'], $collectionFieldDetail);
                }
            }
        }
    }

    public function validate($pathData = [])
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = $this->getData($pathData);
        foreach ($this->validation as $key => $validationDetail) {
            $this->logger->info('validationKey: '.$key);
            if (array_key_exists($key, $data)) {
                foreach ($validationDetail as $validationType => $espectedValue) {
                    $this->logger->info('validation type: '.$validationType.' required: '.$espectedValue.' - data: '.$data[$key]);
                    if (!$this->validateProp($validationType, $espectedValue, $data[$key])) {
                        $this->respJson(['error' => true, 'message' => 'validation failed '.$validationType.'.', 'field' => $key, 'error_type' => 'validation', 'validation_type' => $validationType], 400);
                    }
                }
            } elseif (!array_key_exists($key, $data) && array_key_exists('required', $validationDetail) && ($method == "POST")) {
                $this->respJson(['error' => true, 'message' => 'missing parameter '.$key.'.', 'field' => $key, 'error_type' => 'validation', 'validation_type' => 'required'], 400);
            }
        }
    }

    protected function validateProp($validationType = '', $validationValue = '', $data = '')
    {
        switch ($validationType) {
        case 'min_length':
          return strlen($data) >= $validationValue;
          break;
        case 'max_length':
          return strlen($data) <= $validationValue;
          break;
        case 'required':
          return !empty($data);
        default:
          $this->logger->error('Error undefined validationType.');

          return false;
          break;
      }
    }

    protected function getData($pathData = [])
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
        case 'GET':
          if (count($pathData) > 0) {
              return ['_id' => $pathData[0]];
          }

          return [];
          break;
        case 'POST':
          return $_POST;
          break;
        case 'PUT':
          $_PUT = [];
          parse_str(file_get_contents('php://input'), $_PUT);

          return $_PUT;
          break;
        case 'DELETE':
          if (count($pathData) > 0) {
              return ['_id' => $pathData[0]];
          }

          return [];
          break;
        default:
          $this->logger->error('validation error method.');
          $this->respJson(['error' => true, 'message' => 'validation failed method.'], 400);
          break;
      }
    }

    protected function handleRequest($pathData = [])
    {
        $this->validate($pathData);
        $this->backupTable($this->collectionName);
        parent::handleRequest($pathData);
    }
}

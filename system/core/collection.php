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

    protected function handleRequest($pathData = [])
    {
        $this->backupTable($this->collectionName);
        parent::handleRequest($pathData);
    }
}

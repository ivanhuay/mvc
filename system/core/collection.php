<?php

class Collection extends Rest
{
    public function __construct($collectionName = null, $enabledMethod = null)
    {
        parent::__construct($enabledMethod);
        if (empty($collectionName)) {
            $collectionName = get_called_class();
        }
        $this->collectionName = strtolower($collectionName);
        $this->db = new Database();
        $this->structure = [];
    }

    public function createTable()
    {
        $tableEstructure = '';
        $tableEstructure = '';

        foreach ($this->structure as $key => $value) {
            $tableEstructure .= $key.' '.$value.',';
        }
        $tableEstructure = trim($tableEstructure, ',');
        $query = 'IF EXISTS(SELECT '.$this->collectionName."
            FROM INFORMATION_SCHEMA.TABLES
           WHERE table_schema = '".DB_NAME."'
             AND table_name = '".$this->collectionName."')
THEN
   ....
   ALTER TABLE Tablename...
   ....
ELSE
   ....
   CREATE TABLE ".$this->collectionName.'
	 (
'.$tableEstructure.'
		 )
END IF;';
    }

    private function backupTable($tableName = null)
    {
        $tableName = $this->db->real_escape_string($tableName);

        if (empty($tableName)) {
            $this->logger->error('empty tableName backupTable');

            return false;
        }

        $tableEstructure = '';

        foreach ($this->structure as $key => $value) {
            $tableEstructure .= $key.' '.$value.',';
        }

        $tableEstructure = trim($tableEstructure, ',');

        $tablesResult = $this->db->new_query('SHOW TABLES FROM '.DB_NAME.' WHERE Tables_in_'.DB_NAME." like '".$tableName."%'");

        $this->logger->info('Table Count: '.$tablesResult->num_rows);

        if ($tablesResult->num_rows == 0) {
            $this->db->new_query('CREATE TABLE '.$this->collectionName.'('.$tableEstructure.')');
        } else {
            $tableStructure = $this->db->new_query('describe '.$tableName);
            while ($row = $tableStructure->fetch_assoc()) {
                if (array_key_exists($row['Field'], $this->structure)) {
										
                    $this->logger->info('Field: '.$row['Field'].' -> '.$this->structure[$row['Field']]." type ".$row["Type"]);
                }
            }
        }
    }

    public function handleRequest()
    {
        $this->backupTable($this->collectionName);
    }
}

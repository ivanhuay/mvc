<?php

class ORM extends Database
{
    public function __construct($database = DB_NAME)
    {
        parent:: __construct($database);
        $this->logger = new Logger();
    }
    public function duplicteTable($oldTable, $newTable)
    {
        $oldTable = $this->real_escape_string($oldTable);
        $newTable = $this->real_escape_string($newTable);

        $this->new_query("CREATE TABLE $newTable LIKE $oldTable");
        return $this->new_query("INSERT $newTable SELECT * FROM $oldTable");
    }
    public function alterColumn($tableName, $columnName, $dataType)
    {
        $tableName = $this->real_escape_string($tableName);
        $columnName = $this->real_escape_string($columnName);
        $dataType = $this->real_escape_string($dataType);
        $this->new_query("ALTER TABLE $tableName
        MODIFY COLUMN $columnName $dataType");
    }
    public function addColumn($tableName, $columnName, $dataType)
    {
        $tableName = $this->real_escape_string($tableName);
        $columnName = $this->real_escape_string($columnName);
        $dataType = $this->real_escape_string($dataType);
        $this->new_query("ALTER TABLE $tableName
        ADD COLUMN $columnName $dataType");
    }
}

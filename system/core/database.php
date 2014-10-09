<?
class Database extends mysqli
{
	function __construct($database = DB_NAME){
		parent:: __construct(DB_HOST,DB_USER,DB_PASS,$database);
		if ($this->connect_errno) {
		    printf("Connect failed: %s\n", $this->connect_error);
		    exit();
		}
	}
	public function new_query($sql){
		$resp = $this->query($sql);
		if (!$resp) {
		    printf("Errormessage: %s\n", $this->error);
		}else
		{
			return $resp;
		}
	}
	public function fetch_all($result)
        {
    		$res=array();
			while ($row = $result->fetch_assoc()) {
				$res[]=$row;
           	}

            return $res;
            
        }

}

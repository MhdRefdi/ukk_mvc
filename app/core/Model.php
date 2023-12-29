<?php 

class Model 
{
	private $db_host = DBHOST;
	private $db_name = DBNAME;
	private $db_user = DBUSER;
	private $db_pass = DBPASS;

	protected $table = '';

	protected $conn;
	protected $query;

	public function __construct()
	{
		$dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name;
		$options = [
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];

	    try {
	    	$this->conn = new PDO($dsn, $this->db_user, $this->db_pass, $options);
	    } catch (PDOException $e) {
	    	die($e->getMessage());
	    }
	}

	public function bind($param, $value, $type = null)
	{
	    if (is_null($type)) {
	    	switch (true) {
	    		case is_int($value):
	    			$type = PDO::PARAM_INT;
	    			break;
	    		case is_bool($value):
	    			$type = PDO::PARAM_BOOL;
	    			break;
	    		case is_null($value):
	    			$type = PDO::PARAM_null;
	    			break;
	    		default:
	    			$type = PDO::PARAM_STR;
	    			break;
	    	}
	    }

	    $this->query->bindValue($param, $value, $type);
	}

	public function get()
	{
		$query = "select * from " . $this->table;
		$this->query = $this->conn->prepare($query);
		$this->query->execute();
	    return $this->query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function find(array $params)
	{
	    $query = "select * from " . $this->table . " where $params[0]=:$params[0]";
		$this->query = $this->conn->prepare($query);
	    $this->bind($params[0], $params[1]);
		$this->query->execute();
	    return $this->query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findFirst(array $params)
	{
	    $query = "select * from " . $this->table . " where $params[0]=:$params[0]";
		$this->query = $this->conn->prepare($query);
	    $this->bind($params[0], $params[1]);
		$this->query->execute();
	    return $this->query->fetch(PDO::FETCH_ASSOC);
	}

	public function create($datas)
	{
		$fields = [];
		$values = [];

		foreach ($datas as $key => $data) {
			$fields[] = $key;
			$values[] = ':'.$key;
		}

		$field = implode(',', $fields);
		$value = implode(',', $values);

		$query = "insert into " . $this->table . "({$field}) values({$value})";
		$this->query = $this->conn->prepare($query);
		foreach ($datas as $key => $value) {
			$this->bind($key, $value);
		}
		$this->query->execute();
	    return $this->query->rowCount();
	}

	public function delete(array $params)
	{
	    $query = "delete from " . $this->table . " where $params[0]=:$params[0]";
		$this->query = $this->conn->prepare($query);
	    $this->bind($params[0], $params[1]);
		$this->query->execute();
	    return $this->query->rowCount();
	}
}
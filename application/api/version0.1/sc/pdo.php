<?php
	
	// try {
	// 	$pdo = new PDO($dsn, $user, $pass,array(PDO::ATTR_PERSISTENT => true)); //初始化一个PDO对象
	// 	echo "连接成功<br/>";
	/**
	 * 
	 */
	// $a = new M();
	// var_dump($a);
	// $a->id = 3;
	// $a->name = 4;
	// $a->where(['id'=>3])->update();
	// $a->table('name');
	// $a->name = 4;
	// $a->id = 17;
	// // $a->delete();
	// // unset($a);
	// var_dump($a->get(3));
	class M
	{
		private $dbms='mysql';	   //数据库类型
		private $host='localhost'; //数据库主机名
		private $dbName='jobweb';  //使用的数据库
		private $user='root';	   //数据库连接用户名
		private $pass='root';	   //对应的密码

		private $pdo;
		private $fieldArray = [];
		private $table;
		private $where;
		private $order;
		
		function __construct()
		{
			// $this->dbName = $dbName or $this->dbName;
			// $this->table = $table;
			$this->connect_db();
		}

		function __destruct()
		{
			//关闭长连接
			$this->pdo = null;
			$this->table = '';
			$this->reset();
		}

		function __set($key,$value)
		{
			$this->fieldArray[$key] = $value;
			// var_dump($this->fieldArray);
			// var_dump($this->fieldArray);

		}
		public function connect_db()
		{
			$this->pdo = null;
			$con=sprintf('%s:host=%s;dbname=%s',$this->dbms,$this->host,$this->dbName);
			
			//创建一个长连接pdo实例
			try {
				$this->pdo = new PDO($con,$this->user, $this->pass,array(PDO::ATTR_PERSISTENT => true));
				echo "<script>console.log('连接成功{$this->dbName}')</script>";
			} catch (PDOException $e) {
				die ("Error!: " . $e->getMessage() . "<br/>");
			}
		}
		public function reset()
		{
			$this->fieldArray = [];
			// $this->table = '';
			$this->where = '';
		}
		public function db($value='')
		{
			$this->dbName = $value;
			$this->connect_db();
			return $this;
		}
		public function table($value='')
		{
			$this->table = $value;
			return $this;
		}
		public function where($fieldArray)
		{
			$temp = ' ';
			foreach ($fieldArray as $key => $value) {
				$temp .= $key . '=\'' . $value . '\' AND ';
			}
			$temp = substr($temp,0,-4);
			$this->where = $temp;
			return $this;
		}
		public function add()
		{
			// var_dump($this->fieldArray);
			// echo "<br>";
			// echo "<br>";
			$values_str = '\'' . implode('\',\'',array_values($this->fieldArray)) . '\'';
			$sql = 'INSERT INTO ' . $this->table . '(' . implode(',',array_keys($this->fieldArray)) . ')' . ' VALUES('. $values_str .')';
			echo $sql;
			$return = $this->pdo->exec($sql);
			if ($return) {
				$q = $this->pdo->query('SELECT COUNT(*) FROM '.$this->table);
				$return = $q->fetch()[0];
			}else{
				$return = $this->pdo->errorInfo();
			}
			$this->reset();
			return $return;
		}
		public function update($limit='')
		{
			// $keys = implode(',',array_keys($this->fieldArray));
			// $values = implode(',',array_value($this->fieldArray));
			if($limit){
				$limit = ' LIMIT ' . $limit;
			}
			$temp =' ';
			foreach ($this->fieldArray as $key => $value) {
				$temp .= $key.'=\''.$value.'\',';
			}
			$temp = substr($temp, 0,-1);
			$sql = 'UPDATE '.$this->table.' SET '.$temp .' WHERE '.$this->where;
			echo $sql.'<br>';
			$return = $this->pdo->exec($sql);
			if ($return) {
				$q = $this->pdo->query('SELECT COUNT(*) FROM '.$this->table);
				$return = $q->fetch()[0];
			}else{
				$return = $this->pdo->errorInfo();
			}
			$this->reset();
			return $return;
		}
		public function delete($limit)
		{	
			if(!empty($limit)){
				$limit = ' LIMIT ' . $limit;
			}
			$this->where($this->fieldArray);
			$sql = 'DELETE FROM ' . $this->table . ' WHERE ' .$this->where.$limit;
			$return = $this->pdo->query($sql);
			if ($return) {
				$q = $this->pdo->query('SELECT COUNT(*) FROM '.$this->table);
				$return = $q->fetch()[0];
			}else{
				$return = $this->pdo->errorInfo();
			}
			$this->reset();
			return $return;
		}
		public function get($limit='',$choose_array=[])
		{
			if($limit){
				$limit = ' LIMIT ' . $limit;
			}
			$choose_field = implode(',', $choose_array) ? implode(',', $choose_array) : '*';
			// var_dump(array_values($choose_array));
			$this->where($this->fieldArray);
			$where = $this->where ? ' WHERE ' . $this->where : '';
			$sql = 'SELECT ' . $choose_field . ' FROM ' . $this->table . $where . $limit;
			// echo $sql;
			$result = $this->pdo->prepare($sql);
			$result->execute();
			return $result->fetchAll(PDO::FETCH_ASSOC);

		}

		public function getAll(){
			$sql = 'SELECT * FROM ' . $this->table;
			$result = $this->pdo->prepare($sql);
			$result->execute();
			if ($return) {
				$q = $this->pdo->query('SELECT COUNT(*) FROM '.$this->table);
				$return = $q->fetch()[0];
			}else{
				$return = $this->pdo->errorInfo();
			}
			$this->reset();
			return $result->fetchAll(PDO::FETCH_ASSOC);
		}
		public function query($sql='')
		{
			$result = $this->pdo->prepare($sql);
			$result->execute();
			if ($return) {
				$q = $this->pdo->query('SELECT COUNT(*) FROM '.$this->table);
				$return = $q->fetch()[0];
			}else{
				$return = $this->pdo->errorInfo();
			}
			return $result;
		}

	}
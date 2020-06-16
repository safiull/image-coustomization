<?php

/**
 * Database Class
 */
class Database{
	public $host   = DB_HOST;
	public $user   = DB_USER;
	public $pass   = DB_PASS;
	public $dbname = DB_NAME;

	public $link;
	public $error;

	// connectDB method ta construct function a diye dilam jate eta autometic load hoy.
	function __construct(){
		$this->connectDB();
	}

	private function connectDB(){
		// mysqli hole 1ta built in method, er maddhome databse er sathe connect korechi.
		$this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
		// jodi mysql ta connect na hoy then 1ta error message show korbe
		if (!$this->link) {
			$this->error = "Connection faild".$this->link->connect_error;
		}
	}

	// Insert data
	public function insert($data){
		// ekhane database er sathe connect hobe
		// line function ta user korsi cause eta kon line a error generate kors e seta janar jonno
		$insert_row = $this->link->query($data) or die($this->link->error.__LINE__);
		if ($insert_row) {
			return $insert_row;
		} else{
			return false;
		}
	}

	// Select Data for show 
	public function select($data){
		// ekhane amra select kore data niye asbo all save kora data form databse
		$result = $this->link->query($data) or die($this->link->error.__LINE__);
		if ($result->num_rows > 0) {
			return $result;
		} else{
			return false;
		}
	}

	// Delete Data
	public function delete($data){
		// ekhane amra select kore data niye asbo all save kora data form databse
		$delete_row = $this->link->query($data) or die($this->link->error.__LINE__);
		if ($delete_row) {
			return $delete_row;
		} else{
			return false;
		}
	}
}
<?php

class DbConnect {

    private $conn;

    function __construct() {        
    }
	
    function connect() {

        $this->conn = pg_connect("host=localhost port=5432 dbname=project1 user=postgres password=hello");   

        return $this->conn;
    }

}

?>

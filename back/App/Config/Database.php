<?php
    class Database{
        private $conn;

        public function __construct(){
            $this->conn = new PDO("mysql:host=localhost;dbname=DB_GCMuniguate2", "root", "");
        }

        public function getConnection(){
            return $this->conn;
        }
    }
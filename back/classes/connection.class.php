<?php

Class Connection{
    private $host ="localhost";
    private $user ="root";
    private $pwd ="";
    private $dbName ="turonvenezolano";

    private $pdo;

    public function connect(){
        $dsn = 'mysql:host='. $this->host .';dbname='. $this->dbName;
        try{
            $this->pdo = new PDO($dsn, $this->user, $this->pwd);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->pdo;
        }catch(PDOException $e){
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function close(){
        return $this->pdo = null;
    }
}
<?php

class Dbh {

    private $servername;
    private $username;
    private $pwd;
    private $dbname;
    private $charset;

    protected function connect() {

        $this->servername = "localhost";
        $this->username = "root";
        $this->pwd = "";
        $this->dbname = "examenoefen";
        $this->charset = "utf8mb4";

        try {
            $dsn = "mysql:host=" . $this->servername . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $db = new PDO($dsn, $this->username, $this->pwd);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo "No database connection. Failed:" . $e->getMessage() . "<br/>";
            die();
        }
    }
}

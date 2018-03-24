<?php
// database klasse
// når databasen skal bruges, brug getInstance() - hvis der allerede er en åben forbindelse til databasen, bliver den brugt, ellers bliver en ny forbindelse oprettet.
class DB {
    private static $_instance = null;
    private $_pdo, 
            $_query, 
            $_error = false, 
            $_results, 
            $_count = 0;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') .';dbname=' . Config::get('mysql/dbname'), Config::get('mysql/username'), Config::get('mysql/password'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //new PDO("mysql:host=localhost;dbname=tec_h2_datingweb", 'virtusb', 'Virtus13'); 
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    // hvis vi allerede er forbundet til DB, brug den forbindelse
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    // funktion som binder parametre til værdier, sikrer bl.a. mod SQL injection
    public function query($sql, $params = array()) {
        $this->_error = false; // reset error
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this; // returner nuværende objekt
    }

    
    public function action($action, $table, $where = array()) {
        if(count($where) == 3) { // field, operator og value er krævet
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ";

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields = array()) {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach($fields as $field) {
                $values .= '?';
                if($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO {$table}(" . implode(',', $keys) . ") VALUES ({$values})";

            if(!$this->query($sql, $fields)->error()) {
                return true;
            }

        return false;
    }

    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id} ";

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        return $this->results()[0];
    }

    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }
}
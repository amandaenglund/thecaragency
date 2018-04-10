<?php
    
    session_start();
    
    require("definitions.php");
    
    /**
     * This class is a final class; it means that is not extendable.
     * It is also a singletone class; it means that there is only one instance of it 
     * and the instance can be access by Database::getDB() and it is not possible to 
     * create an instance by using the constructor.
    **/
    final class Database {
        private $mysqli, $errno;
        private static $instance;
        
        private function __construct() {
            $this->mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
            if($this->mysqli->connect_error) die("Connection error");
            $this->mysqli->query("SET NAMES 'latin1'");
        }
        
        public static function getDB() {
            return empty(self::$instance) ? self::$instance = new self() : self::$instance;
        }
        
        public function __destruct() {
            $this->mysqli->close();
        }
        
        public function getError() {
            return $this->errno;
        }
        
        private function bindParams($array) {
            $types  = '';
            $values = array();
            foreach($array as $key => $value) {
                $type = key($value);
                $values[] = &$array[$key][$type];
                $types .= $type;
            }
            
            $values = array_merge(array($types), $values);
            return (count($values) < 2) ? null : $values;
        }

        /**
         * $query is the query string
         * $params is an array of array(type => value)
         * 
         * On error it returns false
         * On select it returns the result as an associative array
         * On delete, insert and update it returns the number of the affected rows
        **/
        public function query($query, $params = array()) {
            $output = false;
            $this->errno = 0;
            if(!is_string($query) || !is_array($params)) return $output;
            $params = $this->bindParams($params);
            $crud   = trim(strtoupper(explode(' ', $query)[0]));
            if(($crud == 'INSERT') && is_null($params)) return $output;
            
            if($stmt = $this->mysqli->prepare($query)) {
                if($params) call_user_func_array(array($stmt, 'bind_param'), $params);
                $stmt->execute();
                if($stmt->errno) $this->errno = $stmt->errno;         
                if($crud == 'SELECT') {
                    $output = array();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) array_push($output, $row);
                    $stmt->free_result();
                } else {
                    $result = $stmt->affected_rows;
                    if(!is_null($result) && ($result >= 0)) $output = $result;
                }
                $stmt->close();
                
            } else $this->errno = $this->mysqli->errno;

            return $output;
        }
        
        public function optimize($table) {
            $this->mysqli->query("OPTIMIZE TABLE $table");
        }
        
        public function insertID() {
            return $this->mysqli->insert_id;
        }
    }
    
    Test::testa

?>
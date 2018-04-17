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
        
        private static $instance;
        private $params = array();
        private $mysqli, $errno;
        
        private function __construct() {
            $this->mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
            if($this->mysqli->connect_error) die("Connection error");
            $this->mysqli->query("SET NAMES 'utf8'");
        }
        
        public static function getDB() {
            if(empty(self::$instance)) self::$instance = new self();
            else self::$instance->params = array();
            return self::$instance;
        }
        
        public function startTransacion() {
            return $this->mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        }
        
        public function commit() {
            return $this->mysqli->commit();
        }
        
        public function rollBack() {
            return $this->mysqli->rollback();;
        }
        
        public function __destruct() {
            $this->mysqli->close();
        }
        
        public function getError() {
            return $this->errno;
        }
        
        private function getParams() {
            $types  = '';
            $values = array();
            foreach($this->params as $key => $value) {
                $type = key($value);
                $values[] = &$this->params[$key][$type];
                $types .= $type;
            }
            
            $values = array_merge(array($types), $values);
            return (count($values) < 2) ? null : $values;
        }
        
        public function clearParams() {
            $this->params = array();
        }
        
        public function addParam($type, $value) {
            array_push($this->params, array($type => $value));
        }
        
        /**
         * $query is the query string
         * $params is an array of array(type => value)
         * 
         * On error it returns false
         * On select it returns the result as an associative array
         * On delete, insert and update it returns the number of the affected rows
        **/
        public function query($query) {
            $output = false;
            $this->errno = 0;
            if(!is_string($query)) return $output;
            $params = $this->getParams();
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
        
        public function insertID() {
            return $this->mysqli->insert_id;
        }
    }
    
?>
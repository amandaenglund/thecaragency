<?php
/** Database connection manager */
class Database {
    protected $_conn;
    protected $_result;
    protected $_numRows;

    public function __construct() {
        if ($this->_conn == null) {
            $this->_conn = new mysqli('localhost', 'amandaenglund', '}u0Nr%a1Z1Ox','amandaenglund');

            if ($this->_conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            } 
        }
    }

    protected function queries($query){
        $this->_result = $this->_conn->query($query);
        $this->_numRows = $this->_result->num_rows;
    }

    protected function selectTabel() {
        $this->queries("SELECT * FROM $this->tabel");
        $tabel = [];
        $object = $this->_result->fetch_object();

        while( $object != null ) {
            array_push($tabel, $object);
            $object = $results->fetch_object();
        }
        $this->connClose();
        return $tabel;
    }

   
    protected function connClose() {
        $this->_conn->close();
    }
    
    
}
// Class Admin
class Admin extends Database {
    
    protected $tabel = 'admins';

    public function login($email) {
        $query = "SELECT email,password FROM";
        $query .= " $this->tabel";
        $query .= " WHERE email =";
        $query .= " '".$email."'";
        $this->queries($query);
        $row = $this->_result->fetch_assoc();
        $this->connClose();
        return $row;
    }
    
    public function signUp($email,$password,$name){
        $query = "INSERT INTO";
        $query .= " $this->tabel";
        $query .= " VALUE('".$email."','".$password."','".$name."',0)";
        $this->queries($query);
    }



  
}
?>
            


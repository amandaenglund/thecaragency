<?php
/** Database connection manager */
class Database {
    protected $conn;
    
    protected function preQuery() {
        if ($this->conn == null) {
            $this->conn = new mysqli('localhost', 'amandaenglund', '}u0Nr%a1Z1Ox','amandaenglund' );

            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            } 
        }
    }

    public function selectTabel() {
        $this->preQuery();
        $results = $this->conn->query("SELECT * FROM $this->tabel");
        $tabel = [];
        $object = $results->fetch_object();

        while( $object != null ) {
            array_push($tabel, $object);
            $object = $results->fetch_object();
        }
        $this->connClose();
        return $tabel;
    }


    
            
    protected function connClose() {
        $this->conn->close();
    }
    
    
}
// Class Admin
class Admin extends Database {
    
    protected $tabel = 'admins';

    public function login($email) {
        $this->preQuery();
        $query = "SELECT email,password FROM";
        $query .= " $this->tabel";
        $query .= " WHERE email =";
        $query .= " '".$email."'";
        $results = $this->conn->query($query);
        $row = $results->fetch_assoc();
        $this->connClose();
        return $row;
    }

  
}
?>

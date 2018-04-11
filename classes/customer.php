<?php
/*
 - Som inloggad kund ska man kunna markera sin beställning som mottagen 

 - När man är inloggad som kund ska man kunna se sina gjorda beställning och om det är skickade eller inte 

 + När besökare gör en beställning ska hen få ett lösenord till sidan där man kan logga in som kund*/
    class customer {
        
        private $customer;
        
        public function __construct() {
            if(isset($_SESSION['customer'])) {
                $params = array();
                $DB = Database::getDB();
                $params['customerID'] = array('i' => $_SESSION['customer']);
                $result = $DB->query("SELECT * FROM Customers WHERE ( customerID = ?)  LIMIT 1", $params);
                if($result) $this->customer = reset($result);
            }
        }
        
        public function isLoggedIn() {
            return isset($this->customer);
        }       
        
        public function logOut() {
            unset($_SESSION['customer']);
        }

        private function pwGenerator(){
            $charRange = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@#$()";
            $pw = null;
            $length = strlen($charRange);
            for ($x = 0; $x < 6; $x++) {
                $n = rand(0, $length);
                $pw .= $charRange[$n];
            }
            return $pw;
        }
        
        
        public function logIn($email, $password) {
            $params = array();
            $DB = Database::getDB(); 
            $params['email'] = array('s' => $email);
            $result = $DB->query("SELECT password FROM Customers WHERE (email = ?)  LIMIT 1", $params);
            if(!$result || !count($result)) return false;
            $result = reset($result)['password'];
            $result = password_verify($password , $result);
            if(!$result) return false;
            else {
                $_SESSION['customer'] = reset($result)['customerID'];
                return true;
            }
        }
        
        public function createcustomer($email, $companyname, $contactname,$phonenumber,$address,$postalcode,$city) {
            $params = array();
            $DB = Database::getDB();
            $password = $this->pwGenerator();
            $params['email'] = array('s' => $email);
            $params['compabyname'] = array('s' => $companyname);
            $params['contactname']  = array('s' => $contactname);
            $params['phonenumber']  = array('i' => $phonenumber);
            $params['pass']  = array('s' => password_hash($password, PASSWORD_BCRYPT));
            $params['address']  = array('s' => $address);
            $params['postalcode']  = array('s' => $postalcode);
            $params['city']  = array('s' => $city);       
            $result= $DB->query("INSERT INTO Customers (email, companyName, contactName,phoneNumber,password,address,postalCode,city) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", $params);
            return isset($result) ? $password : $result;
        }

    }
    
?>
<?php
/*
 - Som inloggad kund ska man kunna markera sin beställning som mottagen 

 - När man är inloggad som kund ska man kunna se sina gjorda beställningar och om det är skickade eller inte 

 + När besökare gör en beställning ska hen få ett lösenord till sidan där man kan logga in som kund*/
    class Customer {
        
        private $customer;
        
        public function __construct() {
            if(isset($_SESSION['CUSTOMER'])) {
                $DB = Database::getDB();
                $DB->addParam('i', $_SESSION['CUSTOMER']);
                $result = $DB->query("SELECT * FROM Customers WHERE (customerID = ?) LIMIT 1");
                if($result) $this->customer = reset($result);
            }
        }

        public function getCustomerDetails(){
            return $this->customer;
        }
        
        public function isSignedIn() {
            return isset($this->customer);
        }       
        
        public function signOut() {
            unset($_SESSION['CUSTOMER']);
        }

        public function getCustomerName(){
            return $this->customer['contactName'];
        }

        private function genPassword(){
            $password  = "";
            $charRange = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@#$()";
            $length    = strlen($charRange);
            for($x = 0; $x < 6; $x++) {
                $n = rand(0, $length);
                $password .= $charRange[$n];
            }
            return $password;
        }
        
        public function signIn($email, $password) {
            $DB = Database::getDB();
            $DB->addParam('s', $email);
            $result = $DB->query("SELECT customerID, password FROM Customers WHERE (email = ?) LIMIT 1");
            if(!$result || !count($result)) return false;
            $result = reset($result);
            if(!password_verify($password , $result['password'])) return false;
            else {
                $_SESSION['CUSTOMER'] = $result['customerID'];
                return true;
            }
        }
        
        public function createCustomer($email, $companyname, $contactname, $phonenumber, $address, $postalcode, $city) {
            $DB = Database::getDB();
            $password = $this->genPassword();
            $DB->addParam('s', $email);
            $DB->addParam('s', $companyname);
            $DB->addParam('s', $contactname);
            $DB->addParam('s', $phonenumber);
            $DB->addParam('s', password_hash($password, PASSWORD_BCRYPT));
            $DB->addParam('s', $address);
            $DB->addParam('s', $postalcode);
            $DB->addParam('s', $city);
            $result  = "INSERT INTO Customers(email, companyName, contactName, phoneNumber, password, address, postalCode, city) ";
            $result .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result  = $DB->query($result);
            return $result ? array('customerID' => $DB->insertID(), 'password' => $password) : false;
        }

    }
    
?>
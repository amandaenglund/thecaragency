<?php

    class Customer {
        
        private $customer;
        
        public function __construct($customerID = 0) {
            if(!$customerID) {
                if(empty($_SESSION['CUSTOMER'])) $customerID = 0;
                else $customerID = $_SESSION['CUSTOMER'];
            }
            
            if($customerID) {
                $DB = Database::getDB();
                $DB->addParam('i', $customerID);
                $result = $DB->query("SELECT * FROM Customers WHERE (customerID = ?) LIMIT 1");
                if($result) $this->customer = reset($result);
            }
        }
        
        public function getCurrent($current) {
            $current--;
            $DB = Database::getDB();
            $DB->addParam('i', 3*$current);
            $result = $DB->query("SELECT * FROM Customers ORDER BY customerID DESC LIMIT ?, 3");
            if(is_array($result)) { foreach($result as $key => $value) { unset($result[$key]['password']); } }
            return (!$result || !count($result)) ? false : $result;
        }
        
        public function getTotal() {
            $DB = Database::getDB();
            $result = $DB->query("SELECT COUNT(*) AS total FROM Customers");            
            $result = (!$result || !count($result)) ? 0 : reset($result)['total'];
            return ($result%3 == 0) ? intval($result/3) : (intval($result/3) + 1);
        }
        
        public function isSignedIn() {
            return isset($this->customer);
        }       
        
        public function signOut() {
            unset($_SESSION['CUSTOMER']);
        }

        public function getCustomer(){
            return isset($this->customer) ? $this->customer : false;
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

        public function gerOrders(){
            $DB = Database::getDB();
            $result = "SELECT o.orderID,op.productID,p.name,o.status FROM Orders AS o INNER JOIN OrderedProducts AS op ON o.orderID = op.orderID INNER JOIN Products AS p ON op.productID = p.productID WHERE o.customerID = 1005 ";
            $result  = $DB->query($result);
            return $result;
        }
    }
    
?>

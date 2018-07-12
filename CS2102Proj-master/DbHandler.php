<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    function __destruct() {
        pg_close($this->conn);
    }

    public function add_user($email, $password, $name, $admin){
        $query_string = 'INSERT INTO users VALUES($1, $2, $3, $4)';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $result = pg_query_params($this->conn, $query_string, array($email, $hash, $name, $admin));
        return $result;
    }

    public function user_exists($email){
        $query_string = 'SELECT * FROM users WHERE email=$1';
        $result = pg_query_params($this->conn, $query_string, array($email));
        if($result !== FALSE){
            if(pg_num_rows($result) > 0){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
    }

    public function login($email, $password){
        $query_string = 'SELECT email, password FROM users WHERE email=$1';
        $result = pg_query_params($this->conn, $query_string, array($email));
        if($result !== FALSE){
            if(pg_num_rows($result) == 1){
                $row = pg_fetch_assoc($result);
                $hash = $row['password'];
                if(password_verify($password, $hash)){
                    return TRUE;
                }
                else{
                    return FALSE;
                }
            }
            else{
                return FALSE;
            }
        }
    }

    public function add_item($item_name, $owner, $fee){
        $query_string = 'INSERT INTO items (item_name, owner, fee) VALUES($1, $2, $3)';
        $result = pg_query_params($this->conn, $query_string, array($item_name, $owner, $fee));
        return $result;
    }

    public function get_all_available_items(){
        $query_string = 'SELECT * FROM items WHERE available=$1';
        $result = pg_query_params($this->conn, $query_string, array(1));
        if($result != FALSE)
            return pg_fetch_all($result);
        else{
            echo "An error occurred";
            return FALSE;
        }
    }

    public function get_all_available_items_for_user($email){
        $query_string = 'SELECT * FROM items WHERE available=$1 AND owner<>$2';
        $result = pg_query_params($this->conn, $query_string, array(1, $email));
        if($result != FALSE)
            return pg_fetch_all($result);
        else{
            echo "An error occurred";
            return FALSE;
        }
    }

    public function get_all_available_items_by_user($email){
        $query_string = 'SELECT * FROM items WHERE available=$1 AND owner=$2';
        $result = pg_query_params($this->conn, $query_string, array(1, $email));
        if($result != FALSE)
            return pg_fetch_all($result);
        else{
            echo "An error occurred";
            return FALSE;
        }
    }

    public function get_all_lended_items_by_user($email){
        $query_string = 'SELECT i.item_id, i.item_name, i.fee, t.borrower FROM items i, transactions t WHERE i.item_id = t.item_id AND i.available = $1 AND i.owner = $2';
        $result = pg_query_params($this->conn, $query_string, array(0, $email));
        if($result != FALSE)
            return pg_fetch_all($result);
        else{
            echo "An error occurred";
            return FALSE;
        }
    }

    public function get_all_borrowed_items_by_user($email){
        $query_string = 'SELECT t.trans_id, i.item_name, i.owner, t.borrow_date, t.return_date FROM items i, transactions t WHERE i.item_id = t.item_id AND t.borrower = $1';
        $result = pg_query_params($this->conn, $query_string, array($email));
        if($result != FALSE)
            return pg_fetch_all($result);
        else{
            echo "An error occurred";
            return FALSE;
        }
    }

    private function is_item_available_for_user($item_id, $email){
        $query_string = 'SELECT * FROM items WHERE owner<>$1 AND item_id = $2 AND available=$3';
        $result = pg_query_params($this->conn, $query_string, array($email, $item_id, 1));
        if($result !== FALSE){
            if(pg_num_rows($result) == 1){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
    }

    private function set_item_availability($item_id, $availability){
        $query_string = 'UPDATE items SET available=$1 WHERE item_id = $2';
        $result = pg_query_params($this->conn, $query_string, array($availability, $item_id));
        if($result !== FALSE){
            if(pg_num_rows($result) == 1){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
    }

    private function create_transaction($item_id, $borrower){
        $query_string = 'INSERT INTO transactions (item_id, borrower, borrow_date) VALUES($1, $2, CURRENT_DATE)';
        $result = pg_query_params($this->conn, $query_string, array($item_id, $borrower));
        if($result != FALSE){
            return $this->set_item_availability($item_id, 0);
        }
        return FALSE;
    }

    public function borrow_item_for_user($item_id, $email){
        if($this->is_item_available_for_user($item_id, $email)){
            if($this->create_transaction($item_id, $email)){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
        else{
            return FALSE;
        }
    }

    private function is_item_unreturned_for_user($trans_id, $email){
        $query_string = 'SELECT * FROM transactions WHERE trans_id=$1 AND borrower=$2 AND return_date IS NULL';
        $result = pg_query_params($this->conn, $query_string, array($trans_id, $email));
        if($result !== FALSE){
            if(pg_num_rows($result) == 1){
                return pg_fetch_array($result)["item_id"];
            }
            else{
                return FALSE;
            }
        }
    }

    private function return_item($trans_id, $item_id){
        $query_string = 'UPDATE transactions SET return_date = CURRENT_DATE WHERE trans_id=$1';
        $result = pg_query_params($this->conn, $query_string, array($trans_id));
        if($result != FALSE){
            return $this->set_item_availability($item_id, 1);
        }
        return FALSE;
    }

    public function return_item_for_user($trans_id, $email){
        $item_id = $this->is_item_unreturned_for_user($trans_id, $email);
        if($item_id){
            if($this->return_item($trans_id, $item_id)){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
        else{
            return FALSE;
        }
    }


  }  

?>
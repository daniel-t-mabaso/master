<?php
// if (!isset($_SESSION)){
//     echo 'Forbidden!<script>
//     window.location = "../../../index.php";
//     </script>';
// }
/*
* THIS CLASS IS USED FOR ALL SYSTEM CALLS THAT DEAL WITH NEWSLETTERS
*/ 
class Alert{
    var $__id;
    var $__symbol;
    var $__low;
    var $__high;
    var $__status;
    var $__last_modified_date;
    var $__creation_date;
    var $__user_email;

    /*CONSTRUCTOR*/
    function __construct(){
        $this->__id = "";
        $this->__symbol = "";
        $this->__low = "";
        $this->__high = "";
        $this->__status = "";
        $this->__last_modified_date = "";
        $this->__creation_date = "";
        $this->__user_email = "Test";
    }
    

    /*SETTERS*/
    function set_details($symbol, $low, $high, $user_email, $id="", $status="active", $creation_date="", $last_modified_date = ""){
        
        $this->__id = $id;
        $this->__symbol = trim($symbol);
        $this->__user_email = $user_email;
        $this->__low = $low;
        $this->__high = $high;
        $this->__status = $status;
        $this->__creation_date = $creation_date;
        if($last_modified_date = ""){
            $last_modified_date = date("Y-m-d H:i:s");
        }
        $this->__last_modified_date = $last_modified_date;
    }

    function set_id($id){
        $this -> __id = $id;
    }
    function set_symbol($symbol){
        $this -> __symbol = $symbol;
    }
    function set_low($low){
        $this -> __low = $low;
    }
    function set_high($high){
        $this -> __high = $high;
    }
    function set_status($status){
        $this -> __status = $status;
    }
    function set_user_email($user_email){
        $this -> __user_email = $user_email;
    }
    function set_creation_date($creation_date){
        $this -> __creation_date = $creation_date;
    }
    function set_last_modified_date($modified_date){
        $this -> __last_modified_date = $modified_date;
    }

    /*GETTERS*/        
    function get_id(){
        return $this -> __id;
    }
    function get_symbol(){
        return $this -> __symbol;
    }
    function get_low(){
        return $this -> __low;
    }
    function get_high(){
        return $this -> __high;
    }
    function get_status(){
        return $this -> __status;
    }
    function get_creation_date(){
        return $this -> __creation_date;
    }
    function get_last_modified_date(){
        return $this -> __last_modified_date;
    }
    function get_user_email(){
        return $this -> __user_email;
    }

    /* Functions */

    function set_details_from_db($db_result){
        if (is_string($db_result)){
            return false;
        }
        while($row = $db_result -> fetch_row()){
            $this -> set_details($row[2],$row[3], $row[4], $row[1], $row[0], $row[5], $row[6], $row[7]);
            return true;
        }
        return false;
    }
    
    function refresh_from_db(){
        $symbol = htmlentities($this->get_symbol());

        $low = htmlentities($this->get_low());
        $low = ($low == "") ? 0 : $low;
        $low = ($low == (int) $low) ? (int) $low : (float) $low;

        $high = htmlentities($this->get_high());
        $high = ($high == "") ? 0 : $high;
        $high = ($high == (int) $high) ? (int) $high : (float) $high;

        $creation_date = htmlentities($this->get_creation_date());
        $user_email = $this->get_user_email();

        $result = db_call("select", "*","alerts", "`user_email` = '$user_email' AND `symbol` = '$symbol' AND `low` = $low AND `high` = $high AND `creation_date` = '$creation_date'");
        // set details from db and return true if done successfully
        return $this->set_details_from_db($result);
    }
    function refresh_from_db_by_id($id=""){
        if ($id == ""){
            $id = $this->get_id();
        }
        if ($id != ""){
            $result = db_call("select", "*","alerts", "`id` = $id");
            return $this->set_details_from_db($result);
        }
        return false;
    }
    function save(){
        $symbol = htmlentities($this-> get_symbol());
        $high = htmlentities($this-> get_high());
        $status = "active";
        $low = htmlentities($this-> get_low());
        $user_email = $this-> get_user_email();
        $creation_date = $this-> get_creation_date();
        $last_modified_date = $this-> get_last_modified_date();
        $alert_id = $this->get_id();

        $variables = "symbol";
        $values = "$symbol";
        if ($high != ""){
            $variables .= ", high";
            $values .= " [~] $high";
        }
        if ($low != ""){
            $variables .= ", low";
            $values .= " [~] $low";
        }
        if ($alert_id != ''){
            $result = db_call("update", "alerts", $variables, $values, "`id` = $alert_id");
        } else {
            $result = db_call("insert", "alerts", "symbol, high, low, status, user_email", "$symbol [~] $high [~] $low [~] $status [~] $user_email");
        }
        $this->refresh_from_db();
        return $result;
    }
    function pause(){
        $alert_id = $this->get_id();
        if($this->refresh_from_db_by_id($alert_id)){
            $status = "inactive";
            $result = db_call("update", "alerts", "status", "$status", "`id` = $alert_id");
            return $result;
        }
        echo "Alert doesn't exist.";
        return false;
    }
    function detele(){
        $alert_id = $this->get_id();
        if($this->refresh_from_db_by_id($alert_id)){
            $status = "deleted";
            $result = db_call("delete", "alerts", "`id` = $alert_id");
            return $result;
        }
        echo "Alert doesn't exist.";
        return false;
    }
    function restore(){
        $alert_id = $this->get_id();
        if($this->refresh_from_db_by_id($alert_id)){
            $status = "active";
            $result = db_call("update", "alerts", "status", "$status", "`id` = $alert_id");
            return $result;
        }
        echo "Alert doesn't exist.";
        return false;
    }
    function has_errors(){
        $result = '<ul>';
        if ($this->get_symbol() == ""){
            $result .= "<li>Stock symbol is required.</li>";
        }else if (strpos($this->get_symbol(), " ") > -1){
            $result .= "<li>Stock symbol cannot contain spaces.</li>";
        }
        if ($this->get_low() == "" && $this->get_high() == ""){
            $result .= "<li>'Price below' or 'Price above' is required.</li>";
        }
        $result .= '</ul>';
        if ($result == '<ul></ul>'){
            $result = false;
        }
        return $result;
    }

    function send_email($subject, $content){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: My Stocks <no-reply@mystocks.co.za>' . "\r\n";    
        $headers .= "Reply-To: My Stocks <support@mystocks.co.za>\r\n";
        
        $message = "<html><body style='font-family: sans-serif; padding: 40px;'>";
        $message .= $content;
        $message .= "</body></html>";
        $to = $this->get_user_email();
        mail($to, $subject, $message, $headers);
    }
}
<?php
// if (!isset($_SESSION)){
//     echo 'Forbidden!<script>
//     window.location = "../../../index.php";
//     </script>';
// }
/*
* THIS CLASS IS USED FOR ALL SYSTEM CALLS THAT REQUIRE NO USER TO BE LOGGED IN OR ACTIVE
* (THINKING ABOUT IT, WE CAN JUST CREATE A USER AT THE START OF EACH SESSION AND USE THAT USER FOR ALL CALLS)
*/ 
class Controller{
    var $__id;
    var $errors;

    /*CONSTRUCTOR*/
    function __construct(){
        $this->__id = "";
        $this->errors = [];
    }
    
    /*SETTERS*/
    function set_details($id){
        $this->__id = $id;
    }
    function set_id($id){
        $this -> __id = $id;
    }
    /*GETTERS*/        
    function get_id(){
        return $this -> __id;
    }

    /* Database related Functions*/
    function get($type, $filter, $condition=""){
        // check if email exists
        $email = $this->get_id();
        $result = db_call("select", $filter, $type, $condition);
        if(is_string($result)){
            array_push($this->errors, "Data not found.");
            return false;
        }else{
            return $result;
        }
    }
}
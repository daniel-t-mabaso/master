<?php
 if (!isset($_SESSION)){
    //start session if not already started.
    session_start();
}
include(__DIR__ . "/class_lib.php");


if(!isset($_SESSION['mystocks_auth']) || !isset($_SESSION['user'])){
    $_SESSION['mystocks_auth'] = false;
    $user = new User;
    $serialized_user = serialize($user);
    $_SESSION['user'] = $serialized_user;
}
$current_user = unserialize($_SESSION['user']);

if (isset($_SESSION['mystocks_auth']) || isset($_SESSION['user'])){
    $ROOT = __DIR__;
    include_once($ROOT . "/controller.php");
    $current_user->get_item_from_db();
}
/***
 * IMPORTANT
 * Ensure that this file is imported on line 1 of the file.
 * **/
?>
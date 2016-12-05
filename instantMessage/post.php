<?php
ini_set("session.cookie_httponly", 1);
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
session_start();
include_once("encrypt.php");
date_default_timezone_set('Asia/Hong_Kong');
if(isset($_SESSION['username'])){

    $groups = loadGroups();
    if (!$groups) die("internal error");

    $text = $_POST["text"];
    $msg = array();
    $msg["msg"] = $text;
    $msg["user"] = $_SESSION["username"];
    $msg["time"] = (new DateTime())->format('H:i:s');
    $msg["type"] = "user_say";
    array_push($groups[$_SESSION["grpid"]]["msgs"], $msg);

    saveGroups($groups);
}
?>

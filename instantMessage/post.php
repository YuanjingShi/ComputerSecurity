<?php
ini_set("session.cookie_httponly", 1);
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
session_start();
date_default_timezone_set('Asia/Hong_Kong');
if(isset($_SESSION['username'])){

    $groups = json_decode(file_get_contents("data/groups.json"), true);
    if (!$groups) die("internal error");

    $text = $_POST["text"];
    $msg = array();
    $msg["msg"] = $text;
    $msg["user"] = $_SESSION["username"];
    $msg["time"] = (new DateTime())->format('H:i:s');
    $msg["type"] = "user_say";
    array_push($groups[$_SESSION["grpid"]]["msgs"], $msg);

    $fp = fopen("data/groups.json", "w") or die();
    fwrite($fp, json_encode($groups));
    fclose($fp);
}
?>

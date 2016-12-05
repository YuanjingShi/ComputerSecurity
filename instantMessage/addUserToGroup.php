<?php
ini_set("session.cookie_httponly", 1);
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
session_start();
include_once("encrypt.php");

echo "ha";
// Safe check
if (!isset($_SESSION["username"])) {
    header("location: login.php");
}
if (!isset($_SESSION["grpid"])) {
    header("location: chooseGroup.php");
}
if (!isset($_POST["inviteUser"]) || $_POST["inviteUser"] == "") {
    header("location: index.php");
}

// Init variables
$id = stripslashes(htmlspecialchars($_POST["inviteUser"]));
$group = $_SESSION["grpid"];
$username = $_SESSION["username"];
$groups = loadGroups();
if (!$groups) die("internal error"); // server parse error
if (!array_key_exists($group, $groups) || !in_array($username, $groups[$group]["users"]))
    header("Location: login.php"); // user not in group or group not exists

// Handle add
if (in_array($id, $groups[$group]["users"])) {
    $_SESSION["addResult"] = 1;
    header("location: index.php");
}
else {
    $users = json_decode(file_get_contents("data/user.json"), true);
    if (!array_key_exists($id, $users)) {
        $_SESSION["addResult"] = 2;
        header("location: index.php");
    }
    else {
        array_push($groups[$group]["users"], $id);
        saveGroups($groups);
        $_SESSION["addResult"] = 0;
        header("location: index.php");
    }
}
?>

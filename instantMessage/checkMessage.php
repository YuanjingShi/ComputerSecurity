<?php
//To prevetn XSS attack
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");
include_once("encrypt.php");

$grpid = $_SESSION["grpid"];
$_SESSION["msg_no"] = $_POST["msg_no"];
$data = loadGroups();
$grp = $data[$grpid];

$msgs = $grp["msgs"]; 
if ($_SESSION["msg_no"] != count($msgs))
{
    $response["msg_no"] = count($msgs);
    $response["msgs"] = array_slice($msgs, $_SESSION["msg_no"] - count($msgs));
}
else $response = array("msg_no" => $_SESSION["msg_no"], "msgs" => array());

echo json_encode($response);

?>
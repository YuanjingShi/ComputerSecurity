<?php
//To prevetn XSS attack
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");

$grpid = $_SESSION["grpid"];
$_SESSION["msg_no"] = $_POST["msg_no"];
$data = json_decode(file_get_contents("data/groups.json"), true);
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

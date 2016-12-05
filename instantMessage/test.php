<?php
include_once("encrypt.php");

$grps = loadJson();
echo "ha";
echo gettype($grps);
print_r($grps);
saveGroups($grps);
$grps = loadGroups();
print_r($grps);
?>

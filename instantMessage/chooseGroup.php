<?php
session_start();
if (isset ( $_GET ['logout'] )) {
    session_destroy();
    header("Location: login.php");
}

if (isset($_POST["chosen"]) && isset($_POST["targetGroup"]))
{
    $grpid = $_POST["targetGroup"];
    $groups = json_decode(file_get_contents("groups.json"), true);
    if (!$groups) die("Internal error");

    $grp = $groups[$grpid];
    if (!array_key_exists($grpid, $groups) || !in_array($_SESSION["username"], $grp["users"])) 
    {
        echo "<span class='error'>The group does not exist,<br> or you are not in the group</span>";
    }
    else 
    {
        $_SESSION["grpid"] = $grpid;
        header("Location: index.php");
    }
}

print_r ($_COOKIE);
?>

<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" href="style.css" media="screen">
    </head>   
    <body>
        <form method="post">
            <label for="targetGroup">Please enter the group ID: </label>
            <br>
            <input name="targetGroup" type="text" autocomplete="off" />
            <button name="chosen" value="chosen" type="submit">Submit</button>
        </form>
    </body>
</html>
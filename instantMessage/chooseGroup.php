<?php
//To prevetn XSS attack
ini_set("session.cookie_httponly", 1);
session_start();
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
include_once("encrypt.php");
date_default_timezone_set('Asia/Hong_Kong');
if (isset ( $_POST ['logout'] )) {
    session_destroy();
    header("Location: login.php");
}

if (isset($_POST["chosen"]) && isset($_POST["targetGroup"]))
{
    $grpid = $_POST["targetGroup"];
    $groups = loadGroups();
    if ($groups === NULL) die("Internal error");

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

if (isset ( $_POST ['create'] )) {
    $groups = loadGroups();
    if ($groups === NULL) die("internal error");
        
    do{
        $grpid = rand(1000000,9999999);
    }while ($groups[$grpid]);
    $_SESSION["grpid"] = $grpid;
        
    //generate the group id and push it into group array
    $groups[$grpid] = array();
    $msgArray = array();
    $userArray = array($_SESSION["username"]);
    
    //initialize the message array and user array 
    $groups[$grpid]["msgs"] = $msgArray;
    $groups[$grpid]["users"] = $userArray;
    
    //initialize the user create message in msg array
    $msg = array();
    $msg["user"] = $_SESSION["username"];
    $msg["time"] = (new DateTime())->format('H:i:s');
    $msg["type"] = "user_create";
    array_push($groups[$grpid]["msgs"], $msg);
    
    saveGroups($groups);
    
    header("Location: index.php");
}

//print_r ($_COOKIE);
?>

<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" href="style.css" media="screen">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    </head>   
    <body>
        <form method="post">
            <label for="targetGroup">Please choose a group or create a new group: </label>
            <br>
            <select name="targetGroup">
            <?php
                $groups = loadGroups();
                if ($groups === NULL) die("Internal error");
                //print_r($_SESSION["username"]);
                foreach (array_keys($groups) as $key){
                    if(in_array($_SESSION["username"], $groups[$key]["users"])){
                        echo "<option value=$key>$key | users: ";
                        foreach (array_values($groups[$key]["users"]) as $user){
                            echo " $user ";
                        }
                        echo "</option>";
                        //print the user name in this group
                    }
                }
            ?>
            </select>

            <button name="chosen" value="chosen" type="submit">Enter</button>
            <button name="create" value="create" type="submit">Create a Group!</button>
            <button name="logout" value="logout" type="submit">Log Out!</button>
            <br/>
            <br/>
        </form>
        <button id="openBtn">Change Password</button>
        <div id="changeDiv" style="display:none">
            <label for="currentPassword">Your current password:</label>
            <input id="cp1" type="password" name="currentPassword" /><br/>
            <label for="newPassword">Input new password:</label>
            <input id="cp2" type="password" name="newPassword" /><br/>
            <label for="newPassword2">Input new password again:</label>
            <input id="cp3" type="password" name="newPassword2" /><br/>
            <p id="changePasswordPrompt" style="color:red"></p>
            <button id="submitBtn">Submit</button>
        </div>
    </body>
    <script type="text/javascript">
        $("#openBtn").click(function() {
            $("#changeDiv").attr("style", "display:block");
            $("#openBtn").attr("disabled", true);
        });
        
        $("#submitBtn").click(function() {
            var cp1 = $("#cp1").val();
            var cp2 = $("#cp2").val();
            var cp3 = $("#cp3").val();
            var prompt = $("#changePasswordPrompt");
            if (cp1=="" || cp2=="" || cp3=="") {
                prompt.html("Please input each field");
                return;
            }
            $.post("changePassword.php",
            {currentPassword: cp1, newPassword: cp2, newPassword2: cp3},
            function(data, status) {
                prompt.html(data);
            });
        });
    </script>
</html>

<?php
session_start();
date_default_timezone_set('Asia/Hong_Kong');
if (isset ( $_POST ['logout'] )) {
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

if (isset ( $_POST ['create'] )) {
    $groups = json_decode(file_get_contents("groups.json"), true);
    if (!$groups) die("internal error");
        
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
    
    //write the updated group info back to the json
    $fp = fopen("groups.json", "w") or die("internal error");
    fwrite($fp, json_encode($groups));
    fclose($fp);
    
    header("Location: index.php");
}

//print_r ($_COOKIE);
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
            <button name="create" value="create" type="submit">Create a Group!</button>
            <button name="logout" value="logout" type="submit">Log Out!</button>
        </form>
        <div>
            <?php
                $groups = json_decode(file_get_contents("groups.json"), true);
                if (!$groups) die("Internal error");
                
                foreach (array_keys($groups) as $key){
                    if(in_array($_SESSION["username"], $groups[$key]["users"]))
                        print_r($key." "."got users: "."<br>");
                        //print the user name in this group
                        foreach (array_values($groups[$key]["users"]) as $user){
                            print_r($user." ");
                        }
                    print_r("<br>");
                }
            ?>
        </div>
    </body>
</html>
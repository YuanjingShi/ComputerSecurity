<?php
session_start();
if (isset ( $_GET ['logout'] )) {
    session_destroy();
    header("Location: login.php");
}

if (isset ($_POST["chosen"]))
{
    header("Location: index.php");
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" href="style.css" media="screen">
    </head>   
    <body>
        <?php 
            if ($_SESSION["not_in_group"] === true) echo "You are not in the group\n";
        ?>
        <form method="post">
            <label for="targetGroup">Please enter the group ID: </label>
            <br>
            <input name="targetGroup" type="text">
            <button name="chosen" value="chosen" type="submit">Submit</button>
        </form>
    </body>
</html>
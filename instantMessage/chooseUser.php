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
        <form action="index.php" method="post">
            <label for="targetUser">Please choose a user with whom you want to chat: </label>
            <br>
            <input name="targetUser" type="text">
            <button name="chosen" value="chosen" type="submit">Submit</button>
        </form>
    </body>
</html>
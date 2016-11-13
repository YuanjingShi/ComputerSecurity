<?php
session_start ();
$file = "user.txt";
$fopen = fopen ( $file, "r");

if ($fopen) {
    $text = explode("\n", fread($fopen, filesize($file)));
}
fclose($fopen);
//echo in_array(henry,$text,true) ? 'It is here' : 'Sorry it is not';


if (isset ( $_POST ['enter'] )) {
    if ($_POST ['name'] != "" && $_POST ['pwd'] != "") {
        $_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
        $_SESSION ['pwd'] = stripslashes ( htmlspecialchars ( $_POST ['pwd'] ) );
        //echo $_POST ['name'];
        if(in_array($_SESSION ['name'], $text,true)){
            $fp = fopen ( "log.html", 'a' );
            fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
            fclose ( $fp );
        }else{
            //echo '<span class="error">User is not registered!</span>';
            $fp1 = fopen ( $file, 'a' );
            fwrite ($fp1, $_SESSION ['name']."\n");
            fclose ( $fp1 );
            $fp = fopen ( "log.html", 'a' );
            fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
            fclose ( $fp );
        }
        header("Location: index.php");
    } else {
        echo '<span class="error">Please input sth valid</span>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
    </head>
    <body>
        <div id="loginform">
            <form method="post">
                <p>Please enter your name to continue:</p>
                <label for="name">User Name:</label>
                <input type="text" name="name" id="name" />
                <label for="pwd">Password:</label>
                <input type="text" name="pwd" id="pwd" />
                <input type="submit" name="enter" id="enter" value="Enter" />
            </form>
        </div>
    </body>
    </html>

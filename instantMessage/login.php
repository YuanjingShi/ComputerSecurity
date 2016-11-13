<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<?php
//session_start ();

function loginForm() {
    echo '
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
   ';
}
loginForm();
$file = "user.txt";
$fopen = fopen ( $file, "r");
//$fread = fread($fopen,filesize($file));

while(!feof($fopen)) {
    $text[] = fgets($fopen);
}
fclose($fopen);
print_r($text);
//$test = ("Henry", "NT", "Irix", "Linux");

if (isset ( $_POST ['enter'] )) {
    if ($_POST ['name'] != "" && $_POST ['pwd'] != "") {
        $_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
        $_SESSION ['pwd'] = stripslashes ( htmlspecialchars ( $_POST ['pwd'] ) );
        echo $_POST ['name'];
        if(in_array($_POST ['name'], $text,true)){
            $fp = fopen ( "log.html", 'a' );
            fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
            fclose ( $fp );
            header("index.php");
        }else{
            echo '<span class="error">User is not registered!</span>';
        }
    } else {
        echo '<span class="error">Please input sth valid</span>';
    }
}

?>

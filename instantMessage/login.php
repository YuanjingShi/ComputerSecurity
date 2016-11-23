<?php
session_start ();
$options = [
  'cost' => 11,
  //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];

if (isset ( $_POST ['enter'] )) {
    if ($_POST ['username'] != "" && $_POST ['pwd'] != "") {
        $user_data = json_decode(file_get_contents("user.json"), true);
        if (!$user_data) echo "internal error";
        $username = $_SESSION ['username'] = stripslashes ( htmlspecialchars ( $_POST ['username'] ) );
        $pwd = $_SESSION ['pwd'] = stripslashes ( htmlspecialchars ( $_POST ['pwd'] ) );
        //echo $_POST ['name'];
        if(array_key_exists($username, $user_data)){
            if(password_verify ($pwd, $user_data[$username]["pwd"])){
                $fp = fopen ( "log.html", 'a' );
                fwrite ( $fp, "<div class='msgln'><i>User " . $username . " has joined the chat session.</i><br></div>" );
                fclose ( $fp );
                header("Location: chooseGroup.php");
            }else{
                echo '<span class="error">Your username/password is not correct!</span>';
            }
        }else{
            //echo '<span class="error">User is not registered!</span>';

            $temp = password_hash($pwd, PASSWORD_BCRYPT, $options);
            $new_user = array();
            $new_user["pwd"] = $temp;
            $user_data[$username] = $new_user; 
            // initialize new user like "username": {"pwd": "eoj3irJKE23%j43lkj"}

            $fp = fopen("user.json", "w") or die("internal error");
            fwrite($fp, json_encode($user_data)); // override database
            fclose($fp);
            $fp = fopen ( "log.html", 'a' );
            fwrite ( $fp, "<div class='msgln'><i>User " . $username . " has joined the chat session.</i><br></div>" );
            fclose ( $fp );
            header("Location: chooseGroup.php");
        }

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
                <label for="username">User Name:</label>
                <input type="text" name="username" id="username" />
                <label for="pwd">Password:</label>
                <input type="password" name="pwd" id="pwd" />
                <input type="submit" name="enter" id="enter" value="Enter" />
            </form>
        </div>
    </body>
    </html>

<?php
if (isset ( $_POST ['enter'] )) {
    if ($_POST ['username'] != "" && $_POST ['pwd'] != "") {
        $user_data = json_decode(file_get_contents("user.json"), true);
        if (!$user_data) die("internal error");
        $username = stripslashes ( htmlspecialchars ( $_POST ['username'] ) );
        session_start ();
        $_SESSION['username'] = $username;
        $pwd = stripslashes(htmlspecialchars($_POST['pwd']));
        $_SESSION['pwd'] = $pwd;
        //echo $_POST ['name'];
		if(!isset($_SESSION['wrong_pw_counter']))
			$_SESSION['wrong_pw_counter'] = 0;
        if(array_key_exists($username, $user_data)){
            if(password_verify ($pwd, $user_data[$username]["pwd"])){
				$_SESSION['username'] = $username;
				$_SESSION['pwd'] = $pwd;
                $fp = fopen("log.html", 'a' );
                fwrite($fp, "<div class='msgln'><i>User " . $username . " has joined the chat session.</i><br></div>" );
                fclose ($fp);
                header("Location: chooseGroup.php");
            }else{
                echo '<span class="error">Your username/password is not correct!</span>';
				if((++$_SESSION['wrong_pw_counter'])%3==0){
					echo "<br><span class='error'>3 x wrong password, try again in 15s!</span>";
					//stay in an idle state for 15 seconds for anti-brute-forcing
					ob_flush();
					flush();
					sleep(15);
				//removed session_destroy();
				}
            }
        }else{
            echo '<span class="error">User is not registered!</span>';
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
                <input type="text" name="username" id="username" autocomplete="off" />
                <label for="pwd">Password:</label>
                <input type="password" name="pwd" id="pwd" autocomplete="off" />
                <input type="submit" name="enter" id="enter" value="Enter" autocomplete="off" />
            </form>
        </div>
		<p>New User? <a href="./register.php">Click here to register!</a></p>
    </body>
 </html>

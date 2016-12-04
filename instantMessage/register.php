<?php
$options = [
  'cost' => 11,
  //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];
$msg = "";
if(!empty($_POST) ){
    do 
    {
        if (!$_POST['g-recaptcha-response'])
        {
            $msg = "Please do the reCaptcha test!";
            break;
        }

        $user_data = json_decode(file_get_contents("data/user.json"), true);
        if (!$user_data) die("internal error");

        $username = stripslashes ( htmlspecialchars ( $_POST ['username'] ) );
        if(array_key_exists($username, $user_data))
        {
            $msg = "User already exists!";
            break;
        }
        if($_POST["pwd_1"]==$_POST["pwd_2"])
        {
            $temp = password_hash($_POST["pwd_1"], PASSWORD_BCRYPT, $options);
            $new_user = array();
            $new_user["pwd"] = $temp;   
            $user_data[$username] = $new_user; 
            // initialize new user like "username": {"pwd": "eoj3irJKE23%j43lkj"}
            $fp = fopen("data/user.json", "w") or die("internal error");
            fwrite($fp, json_encode($user_data)); // override database
            fclose($fp);

            $msg = "Register successfully!";
        }
        else $msg = "Passwords do not match!";
    } while(0);
}


?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script>
            document.getElementById("captcha").onclick = function() 
            {

            }
        </script>
    </head>
    <body>
        <div id="loginform">
			<span class="error"><?php echo $msg; ?></span>
            <form action="register.php" method="post" autocomplete="off">
                <input style="display:none"/>
                <input type="password" style="display:none"/>
                <p>Please enter your information:</p>
                <label for="username">User Name:</label><br>
                <input type="text" name="username" id="username" autocomplete="off" required="required" /><br>
                <label for="pwd_1">Password:</label><br>
                <input type="password" name="pwd_1" id="pwd_1" autocomplete="off" required="required" /><br>
				<label for="pwd_2">Confirm password:</label><br>
                <input type="password" name="pwd_2" id="pwd_2" autocomplete="off" required="required" /><br><br>
                <div style="width: 60%; margin: 0 auto;" class="g-recaptcha" id="captcha" data-sitekey="6LeOyg0UAAAAAEw4kJ5g5fSL5prtXEkzk-R-rxBw"></div>
                <input type="submit" name="register" id="register" value="Register" autocomplete="off" />
            </form>
        </div>
		<p><a href="./login.php">Back to login</a></p>
		
    </body>
    </html>
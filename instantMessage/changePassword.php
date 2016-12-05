<?php
ini_set("session.cookie_httponly", 1);
session_start();
if (!isset($_POST["currentPassword"]) || $_POST["currentPassword"]=="" ||
!isset($_POST["newPassword"]) || $_POST["newPassword"]=="" ||
!isset($_POST["newPassword2"]) || $_POST["newPassword2"]=="" ||
!isset($_SESSION["username"])) {
    echo "Please input each field";
}
else {
    $options = [
        'cost' => 11,
    ];
    $username = $_SESSION["username"];
    $currentPassword = stripslashes(htmlspecialchars($_POST['currentPassword']));
    $newPassword = stripslashes(htmlspecialchars($_POST['newPassword']));
    $newPassword2 = stripslashes(htmlspecialchars($_POST['newPassword2']));
    
    $userData = json_decode(file_get_contents("data/user.json"), true);
    if (!$userData) die("internal error");
    if (array_key_exists($username, $userData) && password_verify($currentPassword, $userData[$username]["pwd"])) {
        if ($newPassword == $newPassword2) {
            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$/", $newPassword)) {
                $temp = password_hash($newPassword, PASSWORD_BCRYPT, $options);
                $userData[$username]["pwd"] = $temp;
                $fp = fopen("data/user.json", "w") or die("internal error");
                fwrite($fp, json_encode($userData));
                fclose($fp);
                echo "Password Changed!";
            }
            else {
                echo "Your password should contain at least 1 upper case letter, 1 lower case letter and 1 number, and should be 8-20 characters long";
            }
        }
        else {
            echo "Your new passwords must match!";
        }
    }
    else {
        echo "Failed to validate your current password, please input correct password";
    }
}
?>
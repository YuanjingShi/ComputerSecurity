<?php
session_start ();
  $options = [
    'cost' => 11,
    //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];
    $temp = password_hash($_SESSION ['pwd'], PASSWORD_BCRYPT, $options);
    //echo $temp;
    //echo $_SESSION['array'][$_SESSION['name']];
    //echo (password_verify ($_SESSION['pwd'], $temp)) ?"right":"wrong";

?>

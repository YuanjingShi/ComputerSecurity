<?php
  $options = [
    'cost' => 11,
    //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];
    $temp = password_hash($_SESSION['pwd'], PASSWORD_BCRYPT, $options);
    echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT, $options)."\n";

    echo password_verify ( $_SESSION['pwd'] , $temp ) ? 'It is here' : 'Sorry it is not';

?>

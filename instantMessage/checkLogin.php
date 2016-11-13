<?php
  $options = [
    'cost' => 11,
    //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];
    echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT, $options)."\n";

    $file = "user.txt";
    $fopen = fopen ( $file, "r");

    if ($fopen) {
        $text = explode("\n", fread($fopen, filesize($file)));
    }
    fclose($fopen);
    print_r($text);
    echo in_array('henry',$text,true) ? 'It is here' : 'Sorry it is not';

?>

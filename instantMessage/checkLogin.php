<?php
var_dump(password_get_info($hash));
// Example
$array(3) {
  ["algo"]=>
  int(1)
  ["algoName"]=>
  string(6) "bcrypt"
  ["options"]=>
  array(1) {
    ["cost"]=>
    int(10)
  }

  //
  $options = [
    'cost' => 11,
    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];
    echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT, $options)."\n";
}

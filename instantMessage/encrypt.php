<?php
$txt = "Sample Text";
$x = mcrypt_encrypt(MCRYPT_3DES, file_get_contents("data/3des.key"), $txt, "stream");
echo $x;
?>

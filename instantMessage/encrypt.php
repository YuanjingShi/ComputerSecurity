<?php
function saveGroups($grps)
{
    $txt = json_encode($grps);
    $key_iv = file_get_contents("data/3des.key");
    $iv = substr($key_iv, 24, 25);
    $key = substr($key_iv, 0, 24);

    $c = mcrypt_encrypt(MCRYPT_3DES, $key, $txt, MCRYPT_MODE_CBC, $iv);
    //write the updated group info back to the json
    echo $c;

    $fp = fopen("data/groups.json", "w");
    fwrite($fp, $c);
    fclose($fp);
    return $c;
}
function loadGroups()
{
    $c = file_get_contents("data/groups.json");
    $key_iv = file_get_contents("data/3des.key");
    $iv = substr($key_iv, 24, 28);
    $key = substr($key_iv, 0, 24);
    $x = mcrypt_decrypt(MCRYPT_3DES, $key, $c, MCRYPT_MODE_CBC, $iv);
    
    return json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $x), true );
}
function loadJson()
{
    return json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', file_get_contents("data/groups.json")), true );
    
}
?>

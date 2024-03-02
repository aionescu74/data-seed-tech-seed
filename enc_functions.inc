<?php
//asa cum arata acaum rezultatul e FOARTE PREDICTIBIL
// ori fac ca prin reconfigurarea de 

$algo = "AES-128-CTR";
$encryption_key = "fastinvoice";
$options = 0;
// Non-NULL Initialization Vector for encryption
$encryption_iv = '8789567891011121';
    

function encode($x)
{
    global $algo;
    global $encryption_key;
    global $options;
    global $encryption_iv;
    
    $iv_length = openssl_cipher_iv_length($algo);
    

    $enc = openssl_encrypt(strval($x), $algo, $encryption_key, $options, $encryption_iv);
    //print($x . ": ". $enc);
    //print("<br>");
    return $enc;
}






function decode($x)
{
    global $algo;
    global $encryption_key;
    //$encryption_key = "aaa";
    global $options;
    global $encryption_iv;
    

    // Use openssl_decrypt() function to decrypt the data
    $dec=openssl_decrypt ($x, $algo, $encryption_key, $options, $encryption_iv);

    // Display the decrypted string
    //echo "Decrypted String: " . $decryption;
    
    return $dec;
}



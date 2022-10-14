<?php

    ## over here we track how long it takes for an encryption to occur
    
    ## (A) PHP PASSWORD HASH
    $start = microtime(true);
    $clear = "MyPassw@rd!23";
    $hash = password_hash($clear, PASSWORD_DEFAULT);
    $endA = microtime(true);
    $verified = password_verify("MyPassw@rd!23", $hash);
    $endB = microtime(true);

    $tenc = $endA - $start;
    $tdec = $endB - $endA;
    $ted = $tenc + $tdec;
    echo "PHP password_hash() + password_verify()<br>";
    echo "Time taken to encode = " . $tenc . " sec <br>";
    echo "Time taken to verify = " . $tdec . " sec <br>";
    echo "Total time taken = " . $ted . " sec <br><br>";


    ## (B) OPENSSL (AES-128-ECB)
    $start = microtime(true);
    $clear = "MyPassw@rd!23";
    $hash = openssl_encrypt($clear, "AES-128-ECB", "mysecretkey1234");
    $endA = microtime(true);
    $decrypt = openssl_decrypt($hash, "AES-128-ECB", "mysecretkey1234");
    $verified = $decrypt == $clear;
    $endB = microtime(true);

    $tenc = $endA - $start;
    $tdec = $endB - $endA;
    $ted = $tenc + $tdec;
    echo "OpenSSL 128-bit AES<br>";
    echo "Time taken to encode = " . $tenc . " sec <br>";
    echo "Time taken to verify = " . $tdec . " sec <br>";
    echo "Total time taken = " . $ted . " sec <br><br>";


    ## (C) CRYPT
    $start = microtime(true);
    $clear = "MyPassw@rd!23";
    $salt = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 14);
    $hash = crypt($clear, $salt);
    $endA = microtime(true);
    $verified = hash_equals($hash, crypt($clear, $hash));
    $endB = microtime(true);

    $tenc = $endA - $start;
    $tdec = $endB - $endA;
    $ted = $tenc + $tdec;
    echo "Salted Crypt + Hash Equals<br>";
    echo "Time taken to encode = " . $tenc . " sec <br>";
    echo "Time taken to verify = " . $tdec . " sec <br>";
    echo "Total time taken = " . $ted . " sec <br><br>";


    ## (D) MD5
    $start = microtime(true);
    $clear = "MyPassw@rd!23";
    $hash = md5($clear);
    $endA = microtime(true);
    $verified = $hash == md5($clear);
    $endB = microtime(true);

    echo "MD5<br>";
    echo "Time taken to encode = " . $tenc . " sec <br>";
    echo "Time taken to verify = " . $tdec . " sec <br>";
    echo "Total time taken = " . $ted . " sec <br><br>";


    ## (E) SALTY MD5 
    $start = microtime(true);
    $clear = "MyPassw@rd!23";
    $salt = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 14);
    $hash = $salt . md5($salt . $clear);
    $endA = microtime(true);
    $dbSalt = substr($hash,0,14);
    $dbPass = substr($hash,14);
    $verified = md5($dbSalt . $clear) == $dbPass;
    $endB = microtime(true);

    echo "MD5 Salted<br>";
    echo "Time taken to encode = " . $tenc . " sec <br>";
    echo "Time taken to verify = " . $tdec . " sec <br>";
    echo "Total time taken = " . $ted . " sec <br><br>";
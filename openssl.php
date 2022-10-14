<?php
  ## (A) ENCRYPT
  ## Keep your secret key somewhere safe
  ## In config file, in a secured folder not publically accessible
  define ("SECRETKEY", "mysecretkey1234");

  ## Encrypt the password using the openssl_encrypt function & your secret key
  function addUser($name, $email, $password){
    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    $this->stmt = $this->pdo->prepare($sql);
    $hash = openssl_encrypt($password, "AES-128-ECB", SECRETKEY);
    
    return $this->stmt->execute([$name, $email, $hash]);
  }
  $pass = addUser("Jane Doe", "jane@doe.com", "password456");


  ## (B) VERIFY
  function login($email, $password){
    $sql = "SELECT * FROM `users` WHERE `email`=?";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute([$email]);
    $user = $this->stmt->fetchAll();
    $plain = openssl_decrypt($user['password'], "AES-128-ECB", SECRETKEY);
    
    return $password==$plain;
  }
  $valid_user = login($_POST['email'], $_POST['password']);


  ## (C) PROTECTING SENSITIVE DATA
  ## Turn address into JSON string & encrypt
  $addresses = [
    "Test Street 1234 Somewhere 2345",
    "Doge Street 1234 Cate Country 2345"
  ];

  $cipher = openssl_encrypt(json_encode($addresses), "AES-128-ECB", SECRETKEY);
  $sql = "INSERT INTO `addresses` (`user_id`, `data`) VALUES (?,?)";
  $this->stmt = $this->pdo->prepare($sql);
  $this->stmt->execute([$id, $cipher]);

  ## Decrypt & JSON decode
  $sql = "SELECT * FROM `address` WHERE `id`=?";
  $this->stmt = $this->pdo->prepare($sql);
  $this->stmt->execute([$id]);
  $address = $this->stmt->fetchAll();
  $address = json_decode(openssl_decrypt($address, "AES-128-ECB", SECRETKEY));

?>
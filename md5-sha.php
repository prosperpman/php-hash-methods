<?php
  ## (A) ENCRYPT
  function addUser($name, $email, $password){
    $hash = md5(password);
    ## $hash = sha1(password);
    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    $this->stmt = $this->pdo->prepare($sql);

    return $this->stmt->execute([$name, $email, $hash]);
  }
  $pass = addUser("Jordan Doe", "jordan@doe.com", "password135");


  ## (B) VERIFICATION
  function login($email, $password){
    $sql = "SELECT * FROM `users` WHERE `email`=?";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute([$email]);
    $user = $this->stmt->fetchAll();
    
    return $user['password'] == md5($password);
    ## return $user['password'] == sha1($password);
  }
  $valid_user = login($_POST['email'], $_POST['password']);


  ## (C) BEEF IT UP - ADDING SALT
  ## Encrypt
  $salt = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 14);
  $hash = $salt . md5($salt . $password);

  ## Decrypt
  ## Extract salt from hash then verify
  $dbSalt = substr($user['password'],0,14);
  $dbPass = substr($user['password'],14);
  if (md5($dbSalt . $password) == $dbPass) { /* CORRECT PASSWORD */ }
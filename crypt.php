<?php
  ## (A) CRYPT THE PASSWORD
  function addUser($name, $email, $password){
    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    $this->stmt = $this->pdo->prepare($sql);
    $hash = crypt($password);

    return $this->stmt->execute([$name, $email, $hash]);
  }
  $pass = addUser("Joy Doe", "joy@doe.com", "password789");


  ## (B) HASH CHECK PASSWORD
  function login($email, $password){
    $sql = "SELECT * FROM `users` WHERE `email`=?";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute([$email]);
    $user = $this->stmt->fetchAll();

    return (hash_equals($user['password'], crypt($password, $user['password'])));
  };
  $valid_user = login($_POST['email'], $_POST['password']);


  ## (C) CRYPT THE PASSWORD WITH SALT ADDED
  function addUserSalt($name, $email, $password){
    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    $this->stmt = $this->pdo->prepare($sql);
    $salt = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 14);
    $hash = crypt($password, $salt);
    
    return $this->stmt->execute([$name, $email, $hash]);
  }
  $pass = addUserSalt("Joy Doe", "joy@doe.com", "password789");
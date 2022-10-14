<?php
  //(A) ENCRYPT
  function addUser($name, $email, $password){
    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    $this->stmt = $this->pdo->prepare($sql);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    return $this->stmt->execute([$name, $email, $hash]);
  }
  $pass = addUser("John Doe", "john@doe.com", "password123");


  ## (B) VERIFY
  function login($email, $password){
    $sql = "SELECT * FROM `users` WHERE `email`=?";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute([$email]);
    $user = $this->stmt->fetchAll();
    
    return password_verify($password, $user['password']);
  }
  $valid_user = login($_POST['email'], $_POST['password']);
?>
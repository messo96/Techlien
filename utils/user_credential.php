<?php

require_once "../boot.php";

//recovery password
if(isset($_POST["recovery_pass"])){
  $dbh -> setPassword($_POST["email"], $_POST["password"]);
  echo '<p>Password setted. Re-welcome :)</p>';
  header("Location:/unibowebsite/login.php");

}


// login form called this .php
if(isset($_POST["login"])){
  $user = $dbh -> login($_POST["email"], $_POST["password"] );
  if(empty($user)){
    ?>
    <p>Password is incorrect or you aren't registered yet</p>
    <a href="../login.php?create_account">Non sei ancora registrato? Clicca qui.</a>
    <?php
  }
  else{
    foreach($user as $data){
      $u = new User($data);
      header("Location:/unibowebsite/profile.php");
    }
  }

}
//register function
else{
  if($dbh -> checkExistingEmail($_POST["email"])){
    echo "<p>Email already registered</p>";
    echo '<a href="/unibowebsite/login.php">Torna al login.</a>';
  }
  else{
    require_once("confirmation_email.php");
    echo $_POST["email"];
    send_emailConfirm($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["username"], $_POST["password"]);
    header( "refresh:3;url=/unibowebsite/login.php" );

    }
  }
 ?>

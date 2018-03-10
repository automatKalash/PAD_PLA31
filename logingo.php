<?php
session_start();//start de sessie
$db = new PDO('mysql:host=localhost;port=3307;dbname=pad;charset=utf8', 'root', 'root');//pdo verbinding voor sql queries
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$name = $_POST['email'];
$password = $_POST['pass'];

//kijkt of de gebruiker voorkomt in klantendatabase
$login = $db->prepare('SELECT * FROM user WHERE email = :useremail');
$login->bindParam(':useremail', $_POST['email']);
$login->execute();
$results = $login->fetch(PDO::FETCH_ASSOC);
$hash = $results['password'];

if (password_verify($_POST['pass'], $hash)){
  session_start();//zowel, dan wordt er een sessie aangemaakt
  $_SESSION['id'] = $results['email'];
  $message = "U bent nu ingelogd. U kunt nu de site gebruiken.";
  echo "<script type='text/javascript'>alert('$message');</script>";//alert voordat de gebruiker naar de webshop komt (komt niet voor)
  header('location:main.php');//stuurt de klant direct naar het webshop
}
  else {
    $error = "Er liep wat mis! Heeft u alle velden correct ingevuld?";
    echo $error;
  }


function encrypt($pass){//Nodig om wachtwoorden te beveiligen. Een versleutelde wachtwoord vorm gaat naar de database
$cost = 10;
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
$salt = sprintf("$2a$%02d$", $cost) . $salt;
$hash = crypt($pass, $salt);
return $hash;
}
?>

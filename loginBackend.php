<?php
session_start();//start de sessie
$db = new PDO('mysql:host=YOURHOST;dbname=YOUR_DB_NAME;charset=utf8', 'USER', 'PASSWORD');//pdo verbinding voor sql queries
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function login($db){//logt in aan de hand van de database
  $name = $_POST['email'];
  $password = $_POST['pass'];

  //kijkt of de gebruiker voorkomt in klantendatabase
  $login = $db->prepare('SELECT '`useremail`, `password`' FROM user WHERE useremail = :username');
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
  }

  function encrypt($pass){//Nodig om wachtwoorden te beveiligen. Een versleutelde wachtwoord vorm gaat naar de database
  $cost = 10;
  $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
  $salt = sprintf("$2a$%02d$", $cost) . $salt;
  $hash = crypt($pass, $salt);
  return $hash;
  }

  function checkFilled(){ //kijkt of de $_POST compleet gevuld is
    $error = false;
    foreach($_POST as $field) {
      if (empty($_POST[$field])) {
        $error = true;
      }
    }
    if ($error) {
      echo "<script type='text/javascript'>alert('Alle velden moeten ingevuld zijn');</script>";
    }
  }

  function register($db){//laat registreren als klant bij de database.
    if ($_POST['pass']&&
        $_POST['email']) {
      $name = $_POST['name'];
      $compare = $db->prepare('SELECT * FROM user WHERE useremail = :name');//verificatie of de gebruikersnaam uniek is
      $compare->bindParam(':name', $name);
      $compare->execute();
      //var_dump($compare);
    if ($compare->fetchAll()) {
      echo "Deze gebruikersnaam is al bezet. Kies een andere.";
      $isduplicate = true;
    }
    if(!$isduplicate){
      $securePass = encrypt($_POST['pass']);//versleutelt de wachtwoord
      $register = $db->prepare('INSERT INTO `user` (`useremail`, `password`) VALUES
          (:useremail, :password);');
      $register->bindParam(':useremail', $_POST['name']);
      $register->bindParam(':password', $securePass);
      $register->execute();//maakt en uitvoert de query
      $register = null;
      $message = "U bent nu geregistreerd. U kunt nu inloggen.";
      echo "<script type='text/javascript'>alert('$message');</script>";//alert boodschap voordat het gaat naar login
      header('location:login.php');
      }
    }
    else {
      echo "Voer a.u.b. alle verplichte velden in";
    }
  }
?>

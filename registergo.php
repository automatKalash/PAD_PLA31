<?php
function encrypt($pass){//Nodig om wachtwoorden te beveiligen. Een versleutelde wachtwoord vorm gaat naar de database
$cost = 10;
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
$salt = sprintf("$2a$%02d$", $cost) . $salt;
$hash = crypt($pass, $salt);
return $hash;
}
session_start();//start de sessie
$db = new PDO('mysql:host=localhost;port=3307;dbname=pad;charset=utf8', 'root', 'root');//pdo verbinding voor sql queries
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
if ($_POST['pass']&&$_POST['email']) {
  $name = $_POST['email'];
  $compare = $db->prepare('SELECT * FROM user WHERE email = :name');//verificatie of de gebruikersnaam uniek is
  $compare->bindParam(':name', $name);
  $compare->execute();
  //var_dump($compare);
  if ($compare->fetchAll()) {
    echo "Deze gebruikersnaam is al bezet. Kies een andere.";
    $isduplicate = true;
  }
  else{
      $securePass = encrypt($_POST['pass']);//versleutelt de wachtwoord
      $register = $db->prepare('INSERT INTO `user` (`email`, `password`) VALUES
          (:useremail, :password);');
      $register->bindParam(':useremail', $_POST['email']);
      $register->bindParam(':password', $securePass);
      $register->execute();//maakt en uitvoert de query
      $register = null;
      $message = "U bent nu geregistreerd. U kunt nu inloggen.";
      echo "<script type='text/javascript'>alert('$message');</script>";//alert boodschap voordat het gaat naar login
      header('location:login.php');//stuurt de klant direct naar het webshop
    }
    }
  else {
  echo "Voer a.u.b. alle verplichte velden in";
  }

?>

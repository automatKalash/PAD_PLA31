<head>
</head>
<body>
  <div class="jumbotron green text-center">
      <h1>PAD</h1>
  </div>

  <div class="topleft">
    <img src="img/logo.png"/>
  </div>

  <div id="mySidenav" class="sidenav">

    <a href="register.php">Registreer nu!</a>
    <?php if (!$_SESSION['username']=="") echo "<a href='uitloggen.php'>Uitloggen</a><a href='klantbeheer.php'>Instellingen account</a>"; else echo "<a href='login.php'>Inloggen</a>";
?>


  </div>
  <div class="info">
    <h2>Registreer als klant</h2><!--De form voor registratie-->
    <form class='well' name="register" method="post" action="registergo.php">
      Email: <input type="text" name="email" id="email"></input><br>
      Wachtwoord: <input type="password" name="pass" id="pass"></input><br>
      <input type="submit" id="btnRegister" value="Registreer"></input><br>
    </form>
  </div>

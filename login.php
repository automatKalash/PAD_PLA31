<body>
  <div class="jumbotron green text-center">
      <h1>PAD</h1>
  </div>

  <div class="topleft">
    <img src="img/logo.png"/>
  </div>

  <div id="mySidenav" class="sidenav">
    <a href="register.php">Registreer nu!</a>
    <a href="login.php">Inloggen</a>
  </div>
  <div class="info">
    <h2>Login</h2><!--form voor inloggen-->
    <form class='well' name="login" method="post" action="logingo.php">
      Email: <input type="text" name="email" id="email"></input><br>
      Password: <input type="password" name="pass" id="pass"></input><br>
      <input type="submit" id="btnLogin" value="Login"></input><br>
    </form>
  </div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="materialize/css/materialize.min.css">
    <script src="js/jquery.js"></script>
    <script src="js/jsCookie.js"></script>
    <link rel="stylesheet" href="alertify/css/alertify.min.css">
    <link rel="stylesheet" href="alertify/css/themes/default.min.css">
    <script src="alertify/js/alertify.min.js"></script>
    <script src="js/functions/resizeEvent.js"></script>
    <script src="materialize/js/materialize.min.js"></script>
    <title>Dating App</title>
    <!-- Cookie consent -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
    <script src="js/cookieNotice.js"></script>
</head>
<?php
$user = new User(); // current user
?>

    <div class="navbar-fixed">
    <nav>
      <div class="nav-wrapper">
        <a href="./" class="brand-logo">Dating App</a>
        <ul class="right hide-on-med-and-down">
        <?php
        if($user->isLoggedIn()) {
            ?>
            <li><a href="logud">Log ud</a></li>
            <li><a href="opdater">Opdater profil</a></li>
            <li><a href="nyadgangskode">Skift adgangskode</a></li>
            <?php
        } else {
            ?>
            <li><a href="logind">Log ind</a></li>
            <li><a href="opret">Registrer</a></li>
            <?php
        }
        ?>
          <li><a href="kontakt">Kontakt os</a></li>
        </ul>
      </div>
    </nav>
  </div>


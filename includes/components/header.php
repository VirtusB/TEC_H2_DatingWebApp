<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="materialize/css/materialize.min.css">
    <script src="js/jquery.js"></script>
    <script src="materialize/js/materialize.min.js"></script>
    <title>Dating App</title>
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
            <li><a href="nyadgangskode">Ændre adgangskode</a></li>
            <?php
        } else {
            ?>
            <li><a href="logind">Log ind</a></li>
            <li><a href="opret">Registrer</a></li>
            <?php
        }
        ?>
          
          

        </ul>
      </div>
    </nav>
  </div>

</head>
<body>
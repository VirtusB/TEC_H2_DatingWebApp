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
    <script async src="alertify/js/alertify.min.js"></script>
    <script src="materialize/js/materialize.min.js"></script>
    <link rel="stylesheet" href="fontawesome\css\fontawesome-all.min.css">
    <link rel="stylesheet" href="materialize/extras/noUiSlider/nouislider.css">
    <script src="materialize/extras/noUiSlider/nouislider.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="css/jquery-ui.theme.min.css">
    <link href="css/material-icons.css" rel="stylesheet">
    <title>Dating App</title>
    <!-- Cookie consent -->
    <link rel="stylesheet" type="text/css" href="css/cookieconsent.min.css" />
    <script async src="js/cookieconsent.min.js"></script>
    <script async src="js/cookieNotice.js"></script>
</head>
<?php
$user = new User(); // current user
?>

<style>
.header-icons {
  font-size: 1rem;
  vertical-align: bottom;
}

.header-material-arrow {
  margin-left: 0 !important;
}

.my-dropdown-content {
  min-width: 216px !important;
}

.header-badge {
  vertical-align: middle;
}

span.label {
  padding: 0.25em;
  border-radius: 0.25em;
  margin: 0.25em;
}

</style>

            
<!-- <span class="new badge red header-badge">4</span>  -->

    <div class="navbar-fixed">
    <nav>
      <div class="nav-wrapper">
        <a href="./" class="brand-logo">Dating App</a>
        <ul class="right hide-on-med-and-down">
        <?php
        if($user->isLoggedIn()) {
            ?> 
            <li><a href="profil" id="header-view-profiles"><i class="fa fa-search header-icons"></i>    Gennemse profiler</a></li>
            <li><a href="opdater"><i class="fa fa-user header-icons"></i>    <?php echo escape($user->data()->username); ?></a></li>
            <li><a href="beskeder"><i class="fa fa-comments header-icons"></i>    Beskeder</a></li>         
            <li><a id="my-dropdown" class="dropdown-trigger" href="#!" data-activates="dropdown1" data-target="dropdown1"><i class="fa fa-cogs header-icons"></i><i class="material-icons right header-material-arrow">arrow_drop_down</i></a></li>
            <!-- Dropdown Structure -->
            <ul id='dropdown1' class='dropdown-content my-dropdown-content'>
            <li><a href="nyadgangskode"><i class="fa fa-key header-icons"></i>    Skift adgangskode</a></li>
            <li><a href="logud"><i class="fa fa-sign-out-alt header-icons"></i>    Log ud</a></li>
          <li><a href="kontakt"><i class="fa fa-envelope header-icons"></i>    Kontakt os</a></li>
            
            </ul>
            
            <?php
        } else {
            ?>
            <li><a href="logind"><i class="fa fa-sign-in-alt header-icons"></i>    Log ind</a></li>
            <li><a href="opret"><i class="fa fa-user-plus header-icons"></i>    Registrer</a></li>
            <li><a href="kontakt"><i class="fa fa-envelope header-icons"></i>    Kontakt os</a></li>
            
            <?php
        }
        ?>
          
        </ul>
      </div>
    </nav>
  </div>

<script>
$(document).ready(function() {

  //$("#my-dropdown").dropdown();

  
  $('#my-dropdown').dropdown({
    belowOrigin: true, 
    alignment: 'right', 
    inDuration: 200,
    outDuration: 150,
    constrain_width: true,
    hover: true, 
    gutter: 1
  });

  $("#header-view-profiles").on("click", function() {
  if ( Cookies.get('scroll') !== null ) {
    Cookies.remove('scroll');
  }
});
});

</script>
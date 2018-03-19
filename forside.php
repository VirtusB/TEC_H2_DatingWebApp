<?php
include 'includes/components/header.php';

require_once 'core/init.php';

if(Session::exists('home')) {
    echo "<p class='flash-home'>" . Session::flash('home') . "</p>";
}

$user = new User(); // current user

if($user->isLoggedIn()) {
    
?>
    <p>Hejsa <a href="profil?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></p>
    <ul>
        <li><a href="logud">Log ud</a></li>
        <li><a href="opdater">Opdater informationer</a></li>
        <li><a href="nyadgangskode">Ændre adgangskode</a></li>
    </ul>
<?php
if($user->hasPermission('admin')) {
    echo '<p>Du er en administrator</p>';
}

} else {
?>
<div class="navbar-fixed">
    <nav>
      <div class="nav-wrapper">
        <a href="#!" class="brand-logo">Logo</a>
        <ul class="right hide-on-med-and-down">
          <li><a href="sass.html">Sass</a></li>
          <li><a href="badges.html">Components</a></li>
        </ul>
      </div>
    </nav>
  </div>
<p>Du er nød til at <a href="logind">logge ind</a> eller <a href="opret">registrere</a></p>
<?php
}
?>

</body>
</html>


<?php

require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';


if(Session::exists('home')) {
    echo "<p class='flash-home'>" . Session::flash('home') . "</p>";
}

$user = new User(); // current user

if($user->isLoggedIn()) {
    
?>
    <p>Hejsa <a href="profil?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></p>
<?php
if($user->hasPermission('admin')) {
    echo '<p>Du er en administrator</p>';
} else if ($user->hasPermission('standard')) {
    echo '<p>Du er en standard bruger</p>';
}

} else {
?>

<p>Du er nød til at <a href="logind">logge ind</a> eller <a href="opret">registrere</a></p>
<?php
}
?>

</main>

<?php include 'includes/components/footer.php' ?>
</body>
</html>


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
<script type="text/javascript">
// slet cookies brugs under oprettelse
if (!document.location.pathname.match(/\opret/)) {
console.log('ikke opret siden');
Cookies.remove("sexVal", { path: '/H2_DatingWebApp' }); 
Cookies.remove("regionVal", { path: '/H2_DatingWebApp' }); 
Cookies.remove("countryVal", { path: '/H2_DatingWebApp' }); 
Cookies.remove("bio_input_cookie", { path: '/' }); 
localStorage.removeItem("profileImg");
}
</script>
</body>
</html>

<?php
// setcookie('sexVal', '', time() -1 , '/');
// setcookie('regionVal', '', time() -1 , '/');
// setcookie('countryVal', '', time() -1 , '/');
// setcookie('bio_input_cookie', '', time() -1 , '/');
?>
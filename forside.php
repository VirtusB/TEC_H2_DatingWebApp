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
// fjern cookies, path er localhost
// Cookies.remove("sexVal", { path: '/DatingWebApp' }); 
// Cookies.remove("regionVal", { path: '/DatingWebApp' }); 
// Cookies.remove("countryVal", { path: '/DatingWebApp' }); 


// fjern cookies, path er dating.virtusb.com
Cookies.remove("sexVal", { path: '/' }); 
Cookies.remove("regionVal", { path: '/' }); 
Cookies.remove("countryVal", { path: '/' });

Cookies.remove("bio_input_cookie", { path: '/' }); 
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
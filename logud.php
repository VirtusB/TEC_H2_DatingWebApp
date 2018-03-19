<?php
require_once 'core/init.php';
include 'includes/components/header.php';


$user = new User();

$user->logout();

Redirect::to('forside');

?>

</body>
</html>
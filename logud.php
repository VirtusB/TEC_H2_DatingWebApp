<?php
include 'includes/components/header.php';
require_once 'core/init.php';

$user = new User();

$user->logout();

Redirect::to('forside');

?>

</body>
</html>
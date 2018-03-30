<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

$user = new User();

$user->logout();

Redirect::to('./');

?>

</main>

<?php include 'includes/components/footer.php' ?>

</body>
</html>
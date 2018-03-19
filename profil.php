<?php
require_once 'core/init.php';
include 'includes/components/header.php';


if(!$username = Input::get('user')) {
    Redirect::to('forside');
} else {
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
    ?>
    <h3><?php echo escape($data->username) ?></h3>
    <p>Fulde navn: <?php echo escape($data->name) ?></p>

    <?php
}

?>

</body>
</html>
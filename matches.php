<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
?>
<style>
    .matches-row {
        margin-top: 7%;
    }
</style>

<div class="row matches-row">
    <h3 style="text-align: center;">Matches</h3>
</div>

<?php
}

?>

</main>

<?php include 'includes/components/footer.php' ?>
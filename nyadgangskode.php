<?php
include 'includes/components/header.php';
include_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'password_current' => array(
                'name' => 'Nuværende adgangskode',
                'required' => true,
                'min' => 6
            ),
            'password_new' => array(
                'name' => 'nye adgangskode',
                'required' => true,
                'min' => 6
            ),
            'password_new_again' => array(
                'name' => 'Nye adgangskode igen',
                'required' => true,
                'min' => 6,
                'matches' => 'password_new'
            )
        ));

        if($validation->passed()) {
            if(!password_verify(Input::get('password_current'), $user->data()->userpassword)) {
                echo 'Nuværende adgangskode er forkert';
            } else {
                $user->update(array(
                    'userpassword' => password_hash(Input::get('password_new'), PASSWORD_DEFAULT)
                ));

                Session::flash('home', 'Din adgangskode er blevet opdateret');
                Redirect::to('forside');
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="password_current">Nuværende adgangskode</label>
        <input type="password" name="password_current" id="password_current">
    </div>

    <div class="field">
        <label for="password_new">Nye adgangskode</label>
        <input type="password" name="password_new" id="password_new">
    </div>

    <div class="field">
        <label for="password_new_again">Nye adgangskode igen</label>
        <input type="password" name="password_new_again" id="password_new_again">
    </div>

    <input type="submit" value="Opdater">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>

</body>
</html>
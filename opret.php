<?php
include 'includes/components/header.php';
require_once 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'name' => 'Brugernavn',
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'Users',
            ),
            'password' => array(
                'name' => 'Adgangskode',
                'required' => true,
                'min' => 6,

            ),
            'password_again' => array(
                'name' => 'Bekræftet adgangskode',
                'required' => true,
                'matches' => 'password',
            ),
            'name' => array(
                'name' => 'Fulde navn',
                'required' => true,
                'min' => 2,
                'max' => 50,
            ),
        ));

        if ($validation->passed()) {
            $user = new User();

            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'userpassword' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                    'name' => Input::get('name'),
                    'joined' => date('Y-m-d H:i:s'),
                    'usergroup' => 1
                ));

                Session::flash('home', 'Du er blevet registreret og kan nu logge ind');
                Redirect::to('forside');
            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error . '<br>';
            }
        }
    }

}
?>

<form action="" method="POST">
    <div class="field">
        <label for="username">Brugernavn</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Vælg en adgangskode</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
        <label for="password_again">Bekræft adgangskode</label>
        <input type="password" name="password_again" id="password_again">
    </div>

    <div class="field">
    <label for="name">Dit fulde navn</label>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Opret">
</form>


</body>
</html>
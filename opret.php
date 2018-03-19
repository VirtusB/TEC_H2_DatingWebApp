<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

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
                echo "<p class='form-validation-error'>{$error}</p>";
            }
        }
    }

}
?>

<div class="row register-row">
    <form action="" method="POST" class="col s12">
    <div class="row">
    <div class="input-field col s12">
      <input id="username" name="username" autocomplete="off" type="text" class="validate" value="<?php echo escape(Input::get('username')); ?>">
      <label for="username">Brugernavn</label>
    </div>
  </div>
      
      <div class="row">
        <div class="input-field col s12">
          <input id="password" name="password" type="password" class="validate" autocomplete="off">
          <label for="password">Vælg en adgangskode</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input id="password_again" name="password_again" class="validate" type="password" autocomplete="off">
          <label for="password_again">Bekræft adgangskode</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input id="name" name="name" type="text" class="validate" autocomplete="off" value="<?php echo escape(Input::get('name')); ?>">
          <label for="name">Dit fulde navn</label>
        </div>
      </div>

      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input class="btn btn-left-margin" type="submit" value="Opret">
     
      
    </form>
  </div>

  </main>

<?php include 'includes/components/footer.php' ?>

</body>
</html>
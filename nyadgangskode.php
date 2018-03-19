<?php
include_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

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
                'name' => 'Bekræftet adgangskode',
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
                echo "<p class='form-validation-error'>{$error}</p>";
            }
        }
    }
}

?>

<div class="row change-password-row">
    <form action="" method="POST" class="col s12">
    <div class="row">
    <div class="input-field col s12">
      <input id="password_current" id="password_current" name="password_current" autocomplete="off" type="password" class="validate">
      <label for="password_current">Nuværende adgangskode</label>
    </div>
  </div>

  <div class="row">
    <div class="input-field col s12">
      <input id="password_new" id="password_new" name="password_new" autocomplete="off" type="password" class="validate">
      <label for="password_new">Nye adgangskode</label>
    </div>
  </div>

  <div class="row">
    <div class="input-field col s12">
      <input id="password_new_again" id="password_new_again" name="password_new_again" autocomplete="off" type="password" class="validate">
      <label for="password_new_again">Bekræft adgangskode</label>
    </div>
  </div>
      
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input class="btn btn-left-margin" type="submit" value="Opdater"> 
      
    </form>
  </div>

  </main>

<?php include 'includes/components/footer.php' ?>

</body>
</html>
<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}



?>

<div class="row update-information-row">
<div class="div-validation-errors">
<?php
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'name' => 'Fulde navn',
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));
        if($validation->passed()) {
            try {
                $user->update(array(
                    'name' => Input::get('name')
                ));

                Session::flash('home', 'Dine informationer er blevet opdateret');
                Redirect::to('forside');

            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                echo "<p class='form-validation-error'>{$error}.</p> ";
            }
        }
    }
}
?>
</div>
    <form action="" method="POST" class="col s12">
    <div class="row">
    <div class="input-field col s12">
      <input id="name" name="name" autocomplete="off" type="text" class="validate" value="<?php echo escape($user->data()->name); ?>">
      <label for="name">Fulde navn</label>
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
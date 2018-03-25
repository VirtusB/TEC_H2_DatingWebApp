<?php
include_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}



?>

<div class="row change-password-row">
<div class="div-validation-errors">
<?php
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
                'name' => 'Nye adgangskode',
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

                // send den nye adgangskode
                $EmailSender = new EmailSender();
                $EmailSender->sendNewPassword();

                Session::flash('home', 'Din adgangskode er blevet opdateret');
                Redirect::to('forside');
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
    <form action="" id="new-password-form" method="POST" class="col s12">
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
    <input class="btn btn-left-margin new-password-btn" type="submit" value="Opdater"> 
      
    </form>
  </div>

  </main>

<?php include 'includes/components/footer.php' ?>

<!-- vertifikation -->
<script>
$(document).ready(function() {
  $(".new-password-btn").on("click", function() {
    errorMessage = "";
    var newPasswordFormValid = true;
    if ($("#password_current").val() == "") {
      errorMessage = "Du er nød til at indtaste din nuværende adgangskode";
      newPasswordFormValid = false;
      $("#new-password-form").submit(function(e) {
        e.preventDefault();
      }); 
    } else if($('#password_current').val().length <= 5) {
      errorMessage = "Nuværende adgangskode skal være mindst 6 karakterer";
      newPasswordFormValid = false;
      $("#new-password-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password_new').val() == "") {
      errorMessage = "Du er nød til at indtaste en ny adgangskode";
      newPasswordFormValid = false;
      $("#new-password-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password_new').val().length <= 5) {
      errorMessage = "Den nye adgangskode skal være mindst 6 karakterer";
      newPasswordFormValid = false;
      $("#new-password-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password_new_again').val() != $('#password_new').val()) {
      errorMessage = "Bekræftet adgangskode skal matche din nye adgangskode";
      newPasswordFormValid = false;
      $("#new-password-form").submit(function(e) {
        e.preventDefault();
      });
    }


    if (!newPasswordFormValid && errorMessage.length > 0) {
      alertify.alert('Fejl', errorMessage, function() {
        alertify.message("OK");
      });
    } else {
      $("#new-password-form")
        .unbind("submit")
        .submit();
    }
  });
});
</script>

</body>
</html>
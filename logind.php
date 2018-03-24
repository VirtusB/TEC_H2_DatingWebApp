<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';


?>



<div class="row login-row">
    <div class="div-validation-errors">
        <?php
            if(Input::exists()) {
                if(Token::check(Input::get('token'))) {
            
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'username' => array(
                            'name' => 'Brugernavn',
                            'required' => true
                        ),
                        'password' => array(
                            'name' => 'Adgangskode',
                            'required' => true
                        )
                    ));
            
                    if($validation->passed()) {
                        $user = new User();
            
                        $remember = (Input::get('remember') === 'on') ? true : false;
                        $login = $user->login(Input::get('username'), Input::get('password'), $remember);
            
                        if($login) {
                            Redirect::to('./');
                        } else {
                            echo 'Brugernavn eller adgangskode forkert';
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
    <form action="" id="login-form" method="POST" class="col s12">
    <div class="row">
    <div class="input-field col s12">
      <input id="username" name="username" autocomplete="off" type="text" class="validate">
      <label for="username">Brugernavn</label>
    </div>
  </div>
      
      <div class="row">
        <div class="input-field col s12">
          <input id="password" name="password" type="password" autocomplete="off" class="validate">
          <label for="password">Adgangskode</label>
        </div>
        <p class="remember-paragraph">
            <input type="checkbox" name="remember" id="remember" />
            <label for="remember">Husk mig</label>
        </p>
      </div>

      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input class="btn btn-left-margin login-btn" type="submit" value="Log ind">
     
      
    </form>
  </div>

  </main>

<?php include 'includes/components/footer.php' ?>

<!-- vertifikation -->
<script>
    $(document).ready(function() {
  $(".login-btn").on("click", function() {
    errorMessage = "";
    var loginFormValid = true;
    if ($("#username").val() == "") {
      errorMessage = "Du er nød til at indtaste et brugernavn";
      loginFormValid = false;
      $("#login-form").submit(function(e) {
        e.preventDefault();
      }); 
    } else if($('#username').val().length <= 1) {
      errorMessage = "Brugernavn skal være mindst 2 karakterer";
      loginFormValid = false;
      $("#login-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#username').val().length > 20) {
      errorMessage = "Brugernavn må maks være 20 karakterer";
      loginFormValid = false;
      $("#login-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password').val() == "") {
      errorMessage = "Du skal indtaste en adgangskode";
      loginFormValid = false;
      $("#login-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password').val().length <= 5) {
      errorMessage = "Adgangskode skal være mindst 6 karakterer";
      loginFormValid = false;
      $("#login-form").submit(function(e) {
        e.preventDefault();
      });
    }


    if (!loginFormValid && errorMessage.length > 0) {
      alertify.alert('Fejl', errorMessage, function() {
        alertify.message("OK");
      });
    } else {
      $("#login-form")
        .unbind("submit")
        .submit();
    }
  });
});
</script>

</body>
</html>
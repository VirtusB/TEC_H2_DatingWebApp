<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';
?>

<div class="row register-row">
    <div class="div-validation-errors">
    <?php
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
                'disallow-only-numbers' => true
            ),
            'password' => array(
                'name' => 'Adgangskode',
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'name' => 'Bekræftet adgangskode',
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'name' => 'Fulde navn',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'email' => array(
                'name' => 'Email',
                'required' => true,
                'validemail' => true
            ),
            'sex_select' => array(
                'name' => 'Køn',
                'zero-int-allowed' => true
            ),
            'region_select' => array(
                'name' => 'Region',
                'required' => true
            ),
            'city' => array(
                'name' => 'By',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'country_select' => array(
                'name' => 'Land',
                'required' => true
            ),
            'age' => array(
                'name' => 'Fødselsdato',
                'required' => true,
                'min-age' => 18,
                'max-age' => 99
            ),
            'bio_input' => array(
                'name' => 'Profil beskrivelse',
                'required' => true,
                'min' => 2,
                'max' => 280
            ),
            'img_input' => array(
                'name' => 'Profil billede'
            )
        ));

        if ($validation->passed()) {
            $user = new User();

            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'userpassword' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                    'name' => ucwords(strtolower(Input::get('name'))),
                    'joined' => date('Y-m-d H:i:s'),
                    'usergroup' => 1,
                    'email' => Input::get('email'),
                    'sex' => Input::get('sex_select'),
                    'regionId' => Input::get('region_select'),
                    'city' => ucfirst(strtolower(Input::get('city'))),
                    'countryId' => Input::get('country_select'),
                    'age' => Input::getDate(Input::get('age')),
                    'profileBio' => ucfirst(Input::get('bio_input')),
                    'imageFile' => Input::getImage('img_input')
                ));        
                
                $EmailSender = new EmailSender();
                $EmailSender->sendWelcomeEmail();
                
                Session::flash('home', 'Du er blevet registreret og kan nu logge ind');
                Redirect::to('forside');
                                 
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo "<p class='form-validation-error'>{$error}.</p> ";
            }
        }
    }

}
?>
    </div>
    <form enctype="multipart/form-data" id="signup-form" action="" method="POST" class="col s12">
        <div class="row">
            <div class="col s6">
                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="username" name="username" autocomplete="off" type="text" class="validate" value="<?php echo escape(Input::get('username')); ?>">
                        <label for="username">Brugernavn</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="password" name="password" type="password" class="validate" autocomplete="off">
                        <label for="password">Vælg en adgangskode</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="password_again" name="password_again" class="validate" type="password" autocomplete="off">
                        <label for="password_again">Bekræft adgangskode</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="email" name="email" class="validate" type="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off">
                        <label for="email" data-error="Forkert">Email</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="name" name="name" type="text" class="validate" autocomplete="off" value="<?php echo escape(Input::get('name')); ?>">
                        <label for="name">Dit fulde navn</label>
                    </div>
                </div>

                

            </div>

            <div class="col s6">
                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12 sex-div">
                            <select onchange="saveSelected(this.value, 'sex-select', 'sexVal');" name="sex_select" id="sex-select">
                                <option disabled="disabled" value="">Køn</option>
                                <option value="1">Mand</option>
                                <option value="0">Kvinde</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field col s12 region-div">
                            <select onchange="saveSelected(this.value, 'region-select', 'regionVal');" name="region_select" id="region-select">
                                <option value="" disabled="disabled" selected="selected">Region</option>
                                <?php
                                    $regions = DB::getInstance()->action('SELECT regionName, regionID', 'Regions', array('1', '=', '1'))->results();
                                    foreach ($regions as $region) {
                                        echo "<option value='{$region->regionID}'  >$region->regionName</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12 city-div">
                            <input id="city" name="city" type="text" class="validate" autocomplete="off" value="<?php echo escape(Input::get('city')); ?>">
                            <label for="city">By</label>
                        </div>
                    </div>

                    <div class="col s6">
                        <div class="input-field col s12 country-div">
                            <select onchange="saveSelected(this.value, 'country-select', 'countryVal');" name="country_select" id="country-select">
                                <option value="" disabled="disabled" selected="selected">Land</option>
                                <?php
                                    $countries = DB::getInstance()->action('SELECT countryName, countryID', 'Countries', array('1', '=', '1'))->results();
                                    foreach ($countries as $country) {
                                        echo "<option value='{$country->countryID}'  >$country->countryName</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>      
                </div>

                

        <div class="row">
          <div class="col s6">        
           <div class="input-field s12 bio-div">
            <textarea id="bio_input" name="bio_input" class="materialize-textarea validate" data-length="280" value="<?php echo escape(Input::get('bio_input')); ?>"></textarea>
            <label id="bio-label" for="bio_input">Beskriv dig selv</label>
                                </div>

                                <div class="row">
                                <div class="input-field col s12">
                                <input type="text" id="age" name="age" class="birthday-picker-input" value="<?php echo escape(Input::get('age')); ?>">
                    <label for="age">Fødselsdato</label>
                        </div>
              </div>


          </div>
          <div class="col s6 profile-img-div">
                <img id="profile-image" src="images/placeholder.jpg">
                <br>
                <input id="img_input" type="file" name="img_input" class="inputfile btn">
                <label id="label_img" for="img_input">Vælg et billede</label>


            </div>
        </div>

  

         
                
                
            </div>

        </div>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <div class="col s12 row-signup-btn">
            <input class="btn signup-btn" type="submit" value="Opret konto">
        </div>

    </form>
</div>

</main>

<?php include 'includes/components/footer.php'?>

<!-- vertifikation -->
<script>
    $(document).ready(function() {
  $(".signup-btn").on("click", function() {
    errorMessage = "";
    var signupFormValid = true;
    if ($("#username").val() == "") {
      errorMessage = "Du er nød til at indtaste et brugernavn";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      }); 
    } else if($('#img_input').get(0).files.length === 0) {
      errorMessage = "Profil billede er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password').val() == "") {
      errorMessage = "Adgangskode er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password').val().length <= 5) {
      errorMessage = "Adgangskode skal være mindt 6 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password_again').val() == "") {
      errorMessage = "Bekræftet adgangskode er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#password_again').val() != $('#password').val()) {
      errorMessage = "Bekræftet adgangskode matcher ikke";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if(/\S+@\S+\.\S+/.test($('#email').val()) == false) {
      errorMessage = "Du er nød til at indtaste en korrekt email adresse";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#name').val() == "") {
      errorMessage = "Fulde navn er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#name').val().length <= 1) {
      errorMessage = "Fulde navn skal være mindst 2 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#name').val().length > 49) {
      errorMessage = "Fulde navn må maks være 50 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#sex-select').val() == null) {
      errorMessage = "Du skal vælge et køn";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#city').val() == "") {
      errorMessage = "By er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#city').val().length <= 1) {
      errorMessage = "Bynavn skal være mindst 2 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#city').val().length > 50) {
      errorMessage = "Bynavn må maks være 50 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#bio_input').val() == "") {
      errorMessage = "Profil beskrivelse er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#bio_input').val().length <= 1) {
      errorMessage = "Profil beskrivelse skal være mindst 2 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#bio_input').val().length > 280) {
      errorMessage = "Profil beskrivelse må maks være 280 karakterer";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#age').val() == "") {
      errorMessage = "Fødselsdato er krævet";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#region-select').val() == null) {
      errorMessage = "Du skal vælge en region";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#country-select').val() == null) {
      errorMessage = "Du skal vælge et land";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    }

    if (!signupFormValid && errorMessage.length > 0) {
      alertify.alert('Fejl', errorMessage, function() {
        alertify.message("OK");
      });
    } else {
      $("#signup-form")
        .unbind("submit")
        .submit();
    }
  });
});
</script>

</body>
</html>
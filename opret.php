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
                'required' => true
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
                    'name' => Input::get('name'),
                    'joined' => date('Y-m-d H:i:s'),
                    'usergroup' => 1,
                    'email' => Input::get('email'),
                    'sex' => Input::get('sex_select'),
                    'regionId' => Input::get('region_select'),
                    'city' => Input::get('city'),
                    'countryId' => Input::get('country_select'),
                    'age' => Input::getDate(Input::get('age')),
                    'profileBio' => Input::get('bio_input'),
                    'imageFile' => Input::getImage('img_input')
                ));

                
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
                            <select name="sex_select" id="sex-select">
                                <option value="" disabled="disabled" selected="selected">Køn</option>
                                <option value="1">Mand</option>
                                <option value="0">Kvinde</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field col s12 region-div">
                            <select name="region_select" id="region-select">
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
                            <select name="country_select" id="country-select">
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
                <!-- <button type="button" id="profile-img-btn" class="btn">Vælg billede</button> -->
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

</body>
</html>
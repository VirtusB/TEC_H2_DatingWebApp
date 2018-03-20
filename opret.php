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
                    'age' => ''
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
    <form id="signup-form" action="" method="POST" class="col s12">
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
                        <input id="email" name="email" class="validate" type="email" autocomplete="off">
                        <label for="email" data-error="Forkert">Email</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="name" name="name" type="text" class="validate" autocomplete="off" value="<?php echo escape(Input::get('name')); ?>">
                        <label for="name">Dit fulde navn</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3 birthday-div">
                    <input type="text" id="age" name="age" class="birthday-picker-input validate" value="<?php echo escape(Input::get('age')); ?>">
                    <label for="age">Fødselsdato</label>
                    <?php
                        $noCommas = str_replace(',', '', Input::get('age')); // fjern kommaer fra datoen
                        $withDashes = str_replace(' ', '-', $noCommas); // erstart mellemrum med bindestreg
                        $month = '';

                        if (preg_match('/\bJanuar\b/',$withDashes)) {
                            $month = str_replace('Januar', '01', $withDashes);
                        } else if (preg_match('/\bFebruar\b/',$withDashes)) {
                            $month = str_replace('Februar', '02', $withDashes);
                        } else if (preg_match('/\bMarts\b/',$withDashes)) {
                            $month = str_replace('Marts', '03', $withDashes);
                        } else if (preg_match('/\bApril\b/',$withDashes)) {
                            $month = str_replace('April', '04', $withDashes);
                        } else if (preg_match('/\bMaj\b/',$withDashes)) {
                            $month = str_replace('Maj', '05', $withDashes);
                        } else if (preg_match('/\bMarts\b/',$withDashes)) {
                            $month = str_replace('Juni', '06', $withDashes);
                        } else if (preg_match('/\bJuli\b/',$withDashes)) {
                            $month = str_replace('Juli', '07', $withDashes);
                        } else if (preg_match('/\bAugust\b/',$withDashes)) {
                            $month = str_replace('August', '08', $withDashes);
                        } else if (preg_match('/\bSeptember\b/',$withDashes)) {
                            $month = str_replace('September', '09', $withDashes);
                        } else if (preg_match('/\bOktober\b/',$withDashes)) {
                            $month = str_replace('Oktober', '10', $withDashes);
                        } else if (preg_match('/\bNovember\b/',$withDashes)) {
                            $month = str_replace('November', '11', $withDashes);
                        } else if (preg_match('/\bDecember\b/',$withDashes)) {
                            $month = str_replace('December', '12', $withDashes);
                        } 

                        //echo $month;

                        $finalDate = date("Y-m-d", strtotime($month));
                        echo $finalDate;                                   
                    ?>
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
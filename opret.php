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
            'email' => array(
                'name' => 'Email',
                'required' => true,
                'validemail' => true,
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
                    'usergroup' => 1,
                    'email' => Input::get('email'),
                ));

                Session::flash('home', 'Du er blevet registreret og kan nu logge ind');
                Redirect::to('forside');
            } catch (Exception $e) {
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
    </div>
    <form action="" method="POST" class="col s12">
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
            </div>

            <div class="col s6">
                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12 sex-div">
                            <select id="sex-select">
                                <option value="" disabled="disabled" selected="selected">Køn</option>
                                <option value="1">Mand</option>
                                <option value="2">Kvinde</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field col s12 region-div">
                            <select id="region-select">
                                <option value="" disabled="disabled" selected="selected">Region</option>
                                <?php                                                                                                        
                                    $regions = DB::getInstance()->action('SELECT regionName, regionID', 'Regions', array('1', '=', '1'))->results();
                                    $x = 1;
                                    foreach($regions as $region) {
                                         echo "<option value='{$region->regionID}'  >$region->regionName</option>"; 
                                         $x++;
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
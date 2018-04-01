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
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
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

        if (empty(Input::get('interest_select'))) {
            echo "<p class='form-validation-error'>Du skal mindst vælge 1 interesse</p> ";
        } else if($validation->passed()) {
            try {
                $user->update(array(
                    'name' => ucwords(strtolower(Input::get('name'))),
                    'email' => Input::get('email'),
                    'sex' => Input::get('sex_select'),
                    'regionId' => Input::get('region_select'),
                    'city' => ucfirst(strtolower(Input::get('city'))),
                    'countryId' => Input::get('country_select'),
                    'profileBio' => ucfirst(Input::get('bio_input')),
                    'imageFile' => (strlen(Input::getImage('img_input')) > 1000 ? Input::getImage('img_input') : $user->data()->imageFile) //Input::getImage('img_input')
                ));

                // slet nuværende interesser
                DB::getInstance()->query('DELETE FROM RS_ProfileInterests WHERE userId = '. $user->data()->id .' ');

                $interests = Input::get('interest_select');
                foreach ($interests as $interest) {
                    DB::getInstance()->query('INSERT INTO RS_ProfileInterests (interestId, userId) VALUES ('. $interest .',  '. $user->data()->id .')');
                }


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
    <form enctype="multipart/form-data" id="update-form" action="" method="POST" class="col s12">


  <div class="row">
            <div class="col s6">
                

                
            <?php //var_dump($user->data()) ?>
                

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="email" name="email" class="validate" type="email" value="<?php echo escape($user->data()->email); ?>" autocomplete="off">
                        <label for="email" data-error="Forkert">Email</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                        <input id="name" name="name" type="text" class="validate" autocomplete="off" value="<?php echo escape($user->data()->name); ?>">
                        <label for="name">Dit fulde navn</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6 offset-m3">
                    <select multiple="multiple" name="interest_select[]" id="interest-select">
                                <option value="" disabled="disabled" selected="selected">Interesser</option>
                                <?php                          
                                    $userInterests = DB::getInstance()->action('SELECT interestId', 'RS_ProfileInterests', array('userId', '=', ' '. $user->data()->id .' '))->results();

                                    $userInterestsSimple = array();
                                    foreach($userInterests as $userInterest) {
                                        $userInterestsSimple[] = $userInterest->interestId;
                                    }
                                    
                                    $interests = DB::getInstance()->action('SELECT interestName, interestID', 'Interests', array('1', '=', '1'))->results();
                                            
                                    ?>
                                    <?php foreach ($interests as $interest) { ?>
                                    <option value="<?php echo $interest->interestID; ?>"<?php echo (in_array($interest->interestID, $userInterestsSimple)) ? ' selected="selected"' : ''; ?>><?php echo $interest->interestName; ?></option>
                                    <?php } ?>  
                        </select>
                    </div>
                </div>

                

            </div>

            <div class="col s6">
                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12 sex-div">
                            <select  name="sex_select" id="sex-select">
                                <option disabled="disabled" value="">Køn</option>
                                <option value="1">Mand</option>
                                <option value="0">Kvinde</option>
                                <?php
                                $userSelectedSex = $user->data()->sex;
                                echo "<script type='text/javascript'>
                                var sexSelect = document.getElementById('sex-select');
                                sexSelect.value = $userSelectedSex;                                                                                               
                                </script>";
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field col s12 region-div">
                            <select name="region_select" id="region-select">
                                <option value="" disabled="disabled">Region</option>
                                <?php
                                    $regions = DB::getInstance()->action('SELECT regionName, regionID', 'Regions', array('1', '=', '1'))->results();
                                    foreach ($regions as $region) {
                                        echo "<option value='{$region->regionID}'  >$region->regionName</option>";
                                    }

                                    $userSelectedRegion = $user->data()->regionId;
                                    echo "<script type='text/javascript'>
                                    var regionSelect = document.getElementById('region-select');
                                    regionSelect.value = $userSelectedRegion;                                                                                               
                                    </script>";
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12 city-div">
                            <input id="city" name="city" type="text" class="validate" autocomplete="off" value="<?php echo escape($user->data()->city); ?>">
                            <label for="city">By</label>
                        </div>
                    </div>
                    
                    <div class="col s6">
                        <div class="input-field col s12 country-div">
                            <select name="country_select" id="country-select">
                                <option value="" disabled="disabled">Land</option>
                                <?php
                                    $countries = DB::getInstance()->action('SELECT countryName, countryID', 'Countries', array('1', '=', '1'))->results();
                                    foreach ($countries as $country) {
                                        echo "<option value='{$country->countryID}'  >$country->countryName</option>";
                                    }

                                    $userSelectedCountry = $user->data()->countryId;
                                    echo "<script type='text/javascript'>
                                    var countrySelect = document.getElementById('country-select');
                                    countrySelect.value = $userSelectedCountry;                                                                                               
                                    </script>";
                                ?>
                            </select>
                        </div>
                    </div>      
                </div>

                

        <div class="row">
          <div class="col s6">        
           <div class="input-field s12 bio-div">
            <textarea id="bio_input" name="bio_input" class="materialize-textarea validate" data-length="280"></textarea>
            <label id="bio-label" for="bio_input">Beskriv dig selv</label>
            <?php 
            $profileBio = $user->data()->profileBio;
            echo "<script type='text/javascript'>
            Cookies.set('bio_input_cookie', '$profileBio');
            
            document.addEventListener('DOMContentLoaded', function() {
                var profileBio = document.getElementById('bio_input');
                profileBio.value = Cookies.get('bio_input_cookie');
            });
                                                                                    
            </script>";
            ?>
                                </div>

                         


          </div>
          <div class="col s6 profile-img-div">
                <img id="profile-image" src="data:image/jpeg;base64,<?php echo escape($user->data()->imageFile);?>" />
                <br>
                <input id="img_input" type="file" name="img_input" class="inputfile btn">
                <label id="label_img" for="img_input">Vælg et billede</label>


            </div>
        </div>

  

         
                
                
            </div>

        </div>







      <div class="row update-btn-row"> 
      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input class="btn btn-left-margin update-btn" type="submit" value="Opdater profil"> 
      </div>
    
      
    </form>
  </div>

  </main>

<?php include 'includes/components/footer.php' ?>

<!-- vertifikation -->
<script>
    $(document).ready(function() {
  $(".update-btn").on("click", function() {
    errorMessage = "";
    var updateFormValid = true;
     if(/\S+@\S+\.\S+/.test($('#email').val()) == false) {
      errorMessage = "Du er nød til at indtaste en korrekt email adresse";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#name').val() == "") {
      errorMessage = "Fulde navn er krævet";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#name').val().length <= 1) {
      errorMessage = "Fulde navn skal være mindst 2 karakterer";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#name').val().length > 49) {
      errorMessage = "Fulde navn må maks være 50 karakterer";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#sex-select').val() == null) {
      errorMessage = "Du skal vælge et køn";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#city').val() == "") {
      errorMessage = "By er krævet";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#city').val().length <= 1) {
      errorMessage = "Bynavn skal være mindst 2 karakterer";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#city').val().length > 50) {
      errorMessage = "Bynavn må maks være 50 karakterer";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#bio_input').val() == "") {
      errorMessage = "Profil beskrivelse er krævet";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#bio_input').val().length <= 1) {
      errorMessage = "Profil beskrivelse skal være mindst 2 karakterer";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#bio_input').val().length > 280) {
      errorMessage = "Profil beskrivelse må maks være 280 karakterer";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    }  else if($('#region-select').val() == null) {
      errorMessage = "Du skal vælge en region";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#country-select').val() == null) {
      errorMessage = "Du skal vælge et land";
      updateFormValid = false;
      $("#update-form").submit(function(e) {
        e.preventDefault();
      });
    }

    if (!updateFormValid && errorMessage.length > 0) {
      alertify.alert('Fejl', errorMessage, function() {
        alertify.message("OK");
      });
    } else {
      $("#update-form")
        .unbind("submit")
        .submit();
    }
  });
});
</script>

</body>
</html>
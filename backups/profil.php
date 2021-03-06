
<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}

if(!$username = Input::get('user')) {
    Redirect::to('forside');
} else {
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
    ?>



    <div class="row profile-row">

        <div class="row sex-region-interests-row">
            <div class="col s3"></div>

            <div class="col s2">
            <select name="sex_select" id="sex-select">
                <option selected="selected" disabled="disabled" value="">Køn</option>
                <option value="1">Mand</option>
                <option value="0">Kvinde</option>
            </select>
            </div>
            <div class="col s2">
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
            <div class="col s2">
            <select multiple name="interest_select" id="interest-select">
                                <option value="" disabled="disabled" selected="selected">Interesser</option>
                                <?php
                                    $interests = DB::getInstance()->action('SELECT interestName, interestID', 'Interests', array('1', '=', '1'))->results();
                                    foreach ($interests as $interest) {
                                        echo "<option value='{$interest->interestID}'  >$interest->interestName</option>";
                                    }
                                ?>
             </select>
            </div>

            <div class="col s3"></div>
        </div> <!-- køn, region og interesser row -->

        <div class="row age-slider-row valign-wrapper">
            <div class="col s3"></div>
            <div class="col s4">
            <div id="age-slider"></div>                     
            </div>
            <div class="col s2">
                <button class="btn filter-search-btn">Filtrer</button>
            </div>
            <div class="col s3"></div>
        </div>

        <div class="divider profile-divider"></div>

       <div class="row view-profiles valign-wrapper center-align">
       <div class="col s1">
        <i id="previous-profile-btn" title="Forrige profil" class="fa fa-arrow-left profile-arrows"></i>
        </div>

        <div class="col s10">
        <div class="row profile-name-age-row">
            <p class="name-paragraph">John Hansen, 31 år</p>
        </div>
        <div class="row">
            <div class="col s6 profile-image-row">
                <img class="profile-image" src="images/male-portait.jpg">
            </div>
            <div class="col s6 profile-info-row">
              <div class="profile-info">
                <p>Bruger siden 21. Marts 2018</p>
                <div class="divider"></div>
                <p class="profile-bio">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet lectus libero. Suspendisse egestas pellentesque ligula et posuere. Fusce arcu velit, viverra vel dolor ut, porta blandit felis. Vivamus pellentesque bibendum sapien quis auctor. Quisque hendrerit iaculiss.
                </p>
                
                <div class="message-user">
                <div class="divider"></div>
                <button class="btn message-user-btn">Send en besked til John</button>
                </div>
                
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col s6 profile-interests">
                <div class="interests-div">
                <h6 class="user-interests-h6">John's interesser</h6>
                <div class="divider interests-divider"></div>
                <ul class="interests-ul">
                 <li>Skating</li>
                 <li>Badeferier</li>
                 <li>Litteratur</li>
                 <li>Biler</li>
                 <li>Pizza</li>
                 <li>Fiskeri</li>
                </ul>
                </div>
             
            </div>
            <div class="col s6 profile-location">
            <div class="location-div">
            <h6 class="user-location-h6">John's placering</h6>
            <div class="divider"></div>
            <div class="inner-location-div">
             <p>Hørsholm</p>
             <p>Danmark, Nordsjælland</p>
            </div>
            </div>
            </div>
        </div>
        </div>

        <div class="col s1">
        <i id="next-profile-btn" title="Næste profil" class="fa fa-arrow-right profile-arrows"></i>
        </div>
       </div>
        



    </div> <!-- profile row -->



    </div>

    <!-- <h3><?php //echo escape($data->username) ?></h3>
    <p>Fulde navn: <?php //echo escape($data->name) ?></p>
    <img class="profile-image" src="data:image/jpeg;base64,<?php //echo escape($data->imageFile);?>" /> -->

    <?php
}

?>

</main>

<?php include 'includes/components/footer.php' ?>

</body>
</html>
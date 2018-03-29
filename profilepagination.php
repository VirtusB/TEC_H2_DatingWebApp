
<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}


    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    
    ?>



    <div class="row profile-row">

        <form method="post">
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
            <select multiple="multiple" name="interest_select[]" id="interest-select">
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

        </form>

        <div class="divider profile-divider"></div>

        <?php

$dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

try {

// Find out how many items are in the table
$total = $dbh->query('
    SELECT
        COUNT(*)
    FROM
        Users
')->fetchColumn();

// How many items to list per page
$limit = 1;

// How many pages will there be
$pages = ceil($total / $limit);

// What page are we currently on?
$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    'options' => array(
        'default'   => 1,
        'min_range' => 1,
    ),
)));

// Calculate the offset for the query
$offset = ($page - 1)  * $limit;

// Some information to display to the user
$start = $offset + 1;
$end = min(($offset + $limit), $total);

// The "back" link
$prevlink = ($page > 1) ? '<a id="previous-profile-php" href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&lsaquo;</span>';



// The "forward" link
$nextlink = ($page < $pages) ? '<a id="next-profile-php" href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a>' : '<span class="disabled">&raquo;</span>';

// Display the paging information
echo '<div style="display:none;" id="paging"><p>', $prevlink, '  ' ,$nextlink, ' </p></div>';

// Prepare the paged query
$stmt = $dbh->prepare('
    SELECT
        *
    FROM
        Users
    ORDER BY
        name
    LIMIT
        :limit
    OFFSET
        :offset
');

// Bind the query params
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

setlocale(LC_ALL, 'danish');


// Do we have any results?
if ($stmt->rowCount() > 0) {
    // Define how we want to fetch the results
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $iterator = new IteratorIterator($stmt);

    // Display the results
    foreach ($iterator as $row) {
        $birth_date = $row['age'];
        $age= date("Y") - date("Y", strtotime($birth_date));

        $joined = $row['joined'];
        $userjoined =  strftime("%d. %B, %Y", strtotime($joined));

        echo '<script type="text/javascript">$(document).ready(function(){ 
            $(".name-paragraph").append(" ' . $row['name'] . ", " . $age . " år" .' ");
            $(".profile-image").attr("src", "data:image/gif;base64,' . $row['imageFile'] .'");
            $("#user-since").append("' . "Bruger siden " . $userjoined .'");
            $(".profile-bio").append("' . $row['profileBio'] .'");
            $(".message-user-btn").append("' . strtok($row['name'], " ") .'");
            $(".user-interests-h6").prepend("' . strtok($row['name'], " ") .'");            
            $(".user-location-h6").prepend("' . strtok($row['name'], " ") .'");            
            $("#user-city").append("' . $row['city'] .'");
            
            

            $("#previous-link").attr("href", $("#previous-profile-php").attr("href"));
            $("#next-link").attr("href", $("#next-profile-php").attr("href"));
        })</script>';
    }

} else {
    echo '<p>No results could be displayed.</p>';
}

} catch (Exception $e) {
echo '<p>', $e->getMessage(), '</p>';
}

?>

       <div class="row view-profiles valign-wrapper center-align">
       <div class="col s1">
       <a id="previous-link" href="">
        <i id="previous-profile-btn" title="Forrige profil" class="fa fa-arrow-left profile-arrows"></i>
        </a>
        </div>

        <div class="col s10">
        <div class="row profile-name-age-row">
            <p class="name-paragraph"></p>
        </div>
        <div class="row">
            <div class="col s6 profile-image-row">
                <img class="profile-image">
            </div>
            <div class="col s6 profile-info-row">
              <div class="profile-info">
                <p id="user-since">
                
                </p>
                <div class="divider"></div>
                <p class="profile-bio">

                </p>
                
                <div class="message-user">
                <div class="divider"></div>
                <button class="btn message-user-btn">Chat med </button>
                </div>
                
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col s6 profile-interests">
                <div class="interests-div">
                <h6 class="user-interests-h6">'s interesser</h6>
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
            <h6 class="user-location-h6">'s placering</h6>
            <div class="divider"></div>
            <div class="inner-location-div">
             <p id="user-city"></p>
             <p id="user-country-region">Danmark, Nordsjælland</p>
            </div>
            </div>
            </div>
        </div>
        </div>

        <div class="col s1">
        <a id="next-link" href="">
        <i id="next-profile-btn" title="Næste profil" class="fa fa-arrow-right profile-arrows"></i>
        </a>
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
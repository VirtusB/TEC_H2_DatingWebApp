<?php
$regions = DB::getInstance()->action('SELECT regionName, regionID', 'Regions', array('1', '=', '1'))->results();
foreach ($regions as $region) {
    echo "<option value='{$region->regionID}'>$region->regionName</option>";
}



<?php
$interests = DB::getInstance()->action('SELECT interestName, interestID', 'Interests', array('1', '=', '1'))->results();
foreach ($interests as $interest) {
    echo "<option value='{$interest->interestID}'  >$interest->interestName</option>";
}

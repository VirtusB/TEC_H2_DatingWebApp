<?php

$enable_caching = false;

if ($enable_caching == true && !filemtime('cache/cache.txt') < time()-1*3600) {
    echo unserialize(file_get_contents('cache/cache.txt'));
    return;
}

require_once 'core/init.php';

$user = new User($username);
$userData = $user->data();


$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");
mysqli_set_charset($conn,"utf8");

mysqli_query($conn, 'set lc_time_names = "da_DK";');

$userQuery = '
SELECT id, name, imageFile, DATE_FORMAT(joined, "%d. %M %Y") as joined, profileBio, city, Countries.countryName, Regions.regionName, sex, FLOOR(DATEDIFF(NOW(),age)/365) as age
FROM Users
LEFT JOIN Regions ON Regions.regionID = Users.regionId
LEFT JOIN Countries ON Countries.countryID = Users.countryId
WHERE NOT (id= ' . $userData->id . ')
AND id NOT IN(SELECT match_to_id FROM Matches WHERE match_from_id = '. $userData->id .')
ORDER BY id
';

$interestsQuery = '
SELECT interestName
FROM Interests
JOIN RS_ProfileInterests ON Interests.interestID = RS_ProfileInterests.interestId
AND NOT (id= ' . $userData->id . ')
AND id NOT IN(SELECT match_to_id FROM Matches WHERE match_from_id = '. $userData->id .')
ORDER BY id
';

$userResult = mysqli_query($conn, $userQuery);

$userArray = array();
while($row = mysqli_fetch_array($userResult)) {

    $userInterests = DB::getInstance()->action('SELECT interestName', 'Interests JOIN RS_ProfileInterests ON Interests.interestID = RS_ProfileInterests.interestId', array('userId', '=', ' '. $row['id'] .' '))->results();
    $userInterestsSimple = array();
    foreach($userInterests as $userInterest) {
        $userInterestsSimple[] = $userInterest->interestName;
    }

    $userArray[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'interests' => $userInterestsSimple,
        'imageFile' => $row['imageFile'],
        'joined' => $row['joined'],
        'profileBio' => $row['profileBio'],
        'city' => $row['city'],
        'countryName' => $row['countryName'],
        'regionName' => $row['regionName'],
        'sex' => $row['sex'],
        'age' => $row['age']
    );
}

file_put_contents('cache/cache.txt', serialize(json_encode(array('Users' => $userArray))));

echo json_encode(array('Users' => $userArray));
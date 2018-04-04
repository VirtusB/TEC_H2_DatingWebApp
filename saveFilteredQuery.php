<?php
require_once 'core/init.php';

$user = new User($username);
$data = $user->data();


$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");

mysqli_set_charset($conn,"utf8");

$result = mysqli_query($conn,'
SELECT id, name, imagefile, joined, profileBio, city, Countries.countryName, Regions.regionName, sex, FLOOR(DATEDIFF(NOW(),age)/365) as age
FROM Users
LEFT JOIN Regions ON Regions.regionID = Users.regionId
LEFT JOIN Countries ON Countries.countryID = Users.countryId
WHERE NOT (id= ' . $data->id . ')
AND id NOT IN(SELECT match_to_id FROM Matches WHERE match_from_id = '. $data->id .')
ORDER BY name
');




$table=array();
while($row = mysqli_fetch_object($result)){
 array_push($table, $row);
  unset($row);
}


if (file_put_contents("filteredQuery.json", json_encode(array('filteredQuery' => $table)))) {
    //echo 'filteredQuery blev gemt<br>';
}


$interestsArray = array();
$interests = mysqli_query($conn,'
SELECT RS_ProfileInterests.userId, RS_ProfileInterests.interestId, Interests.interestName
FROM RS_ProfileInterests
LEFT JOIN Interests ON Interests.interestID = RS_ProfileInterests.interestId
order by userid
');

while($row = mysqli_fetch_object($interests)){
    array_push($interestsArray, $row);
     unset($row);
   }
   header('Content-Type: application/json');
//echo json_encode($interestsArray);

//interesser = $interestsArray

$queryArray = json_decode(file_get_contents("filteredQuery.json"), true);

$extraTest = array(
    'interestId' => '1',
    'interestName' => 'Biler'
);
$queryArray[] = $extraTest;
$final_data = json_encode($queryArray);
echo $final_data;

mysqli_close($conn);

?>
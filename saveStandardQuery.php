<?php
require_once 'core/init.php';

$user = new User($username);
$data = $user->data();


$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");

mysqli_set_charset($conn,"utf8");

$result = mysqli_query($conn,'
SELECT id, name, imagefile, joined, profileBio, city, Countries.countryName, Regions.regionName, sex
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


if (file_put_contents("standardQuery.json", json_encode(array('standardQuery' => $table)))) {
    echo 'Filen blev gemt';
}



mysqli_close($conn);

?>
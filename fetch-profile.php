<?php
require_once 'core/init.php';

$user = new User($username);
$userData = $user->data();


$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");
mysqli_set_charset($conn,"utf8");

$query = '
SELECT id, name, imageFile, joined, profileBio, city, Countries.countryName, Regions.regionName, sex, FLOOR(DATEDIFF(NOW(),age)/365) as age
FROM Users
LEFT JOIN Regions ON Regions.regionID = Users.regionId
LEFT JOIN Countries ON Countries.countryID = Users.countryId
WHERE NOT (id= ' . $userData->id . ')
AND id NOT IN(SELECT match_to_id FROM Matches WHERE match_from_id = '. $userData->id .')
AND id = '.$_POST['id'].'
ORDER BY name
';

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_array($result)) {
    $data['id'] = $row['id'];
    $data['name'] = $row['name'];
    $data['imageFile'] = $row['imageFile'];
    $data['joined'] = $row['joined'];
    $data['profileBio'] = $row['profileBio'];
    $data['city'] = $row['city'];
    $data['countryName'] = $row['countryName'];
    $data['regionName'] = $row['regionName'];
    $data['sex'] = $row['sex'];
    $data['age'] = $row['age'];
}

echo json_encode($data);
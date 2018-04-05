<?php
require_once 'core/init.php';

$user = new User($username);
$data = $user->data();


$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");
mysqli_set_charset($conn,"utf8");


$record_per_page = 1;
$page = '';
$output = '';

if (isset($_POST['page'])) {
    $page = $_POST['page'];
} else {
    $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = '
SELECT id, name, imageFile, joined, profileBio, city, Countries.countryName, Regions.regionName, sex, FLOOR(DATEDIFF(NOW(),age)/365) as age
FROM Users
LEFT JOIN Regions ON Regions.regionID = Users.regionId
LEFT JOIN Countries ON Countries.countryID = Users.countryId
WHERE NOT (id= ' . $data->id . ')
AND id NOT IN(SELECT match_to_id FROM Matches WHERE match_from_id = '. $data->id .')
ORDER BY name
LIMIT
    '.$start_from.', '.$record_per_page.'
';

$result = mysqli_query($conn, $query);

$output .= '
<table class="highlight centered">
    <tr>
        <th>Navn</th>
        <th>Alder</th>
        <th>Billede</th>
        <th>Joined</th>
        <th>Bio</th>
        <th>By</th>
        <th>Land</th>
        <th>Region</th>
        <th>Køn</th>
    </tr>
';



while($row = mysqli_fetch_array($result)) {
    $output .= '
        <tr>
            <td>'.$row['name'].'</td>
            <td>'.$row['age'].'</td>
            <td><img src="data:image/gif;base64,' . $row['imageFile'] .'"></td>
            <td>'.$row['joined'].'</td>
            <td>'.$row['profileBio'].'</td>
            <td>'.$row['city'].'</td>            
            <td>'.$row['countryName'].'</td>
            <td>'.$row['regionName'].'</td>
            <td>'.$row['sex'].'</td>
        </tr>
    ';
}

$output .= '
</table>
<br>
<div class="center-align">
';

// få mængden af profiler
$page_query = '
SELECT id, name, imagefile, joined, profileBio, city, Countries.countryName, Regions.regionName, sex, FLOOR(DATEDIFF(NOW(),age)/365) as age
FROM Users
LEFT JOIN Regions ON Regions.regionID = Users.regionId
LEFT JOIN Countries ON Countries.countryID = Users.countryId
WHERE NOT (id= ' . $data->id . ')
AND id NOT IN(SELECT match_to_id FROM Matches WHERE match_from_id = '. $data->id .')
ORDER BY name
';

$page_result = mysqli_query($conn, $page_query);
$total_records = mysqli_num_rows($page_result);

$total_pages = ceil($total_records / $record_per_page);

for ($i = 1; $i <= $total_pages; $i++) {
    $output .= '
        <span id="'.$i.'" class="pagination_link">'.$i.'</span>
    ';
}

echo $output;




?>
<?php

setlocale(LC_TIME, "da_DK");
date_default_timezone_set('Europe/Copenhagen');

$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");

$current_user_id = $_POST['current_user_id'];
$target_user_id = $_POST['target_user_id'];

$dislikeQuery = '
INSERT INTO Matches (match_from_id, match_to_id, status, matchdate)
VALUES ('.$current_user_id.', '.$target_user_id.', 1, NOW())
';



if (mysqli_query($conn, $dislikeQuery)) {
    echo '
    <script>
    alertify.notify("Synes godt om!", "success", 4, function(){console.log("dismissed");});    
    </script>
    ';
} else {
    echo 'fejl';
}
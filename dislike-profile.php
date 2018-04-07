<?php

$conn = mysqli_connect("127.0.0.1","virtusbc_h2_user","rootpwdating","virtusbc_tec-dating");

$current_user_id = $_POST['current_user_id'];
$target_user_id = $_POST['target_user_id'];

$dislikeQuery = '
INSERT INTO Matches (match_from_id, match_to_id, status, matchdate)
VALUES ('.$current_user_id.', '.$target_user_id.', 0, NOW())
';



if (mysqli_query($conn, $dislikeQuery)) {
    echo '
    <script>
    alertify.notify("Synes ikke godt om!", "error", 4, function(){console.log("dismissed");});    
    </script>
    ';
} else {
    echo 'fejl';
}
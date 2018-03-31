<?php


$message_to_pass = $_POST['message_to_pass'];
$message_to_user = $_POST['message_to_user'];
$message_from_user = $_POST['message_from_user'];


$response = "Besked sendt : " . $message_to_pass;

//echo json_encode($response);



$dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

// Få mængden af beskeder til brugeren
$stmt = $dbh->prepare('
    INSERT INTO Messages
        (msg_from_id, msg_to_id, msg_body, msg_date)
    VALUES
        ('. $message_from_user .', '. $message_to_user .', "'. $message_to_pass .'", NOW())
');



if($stmt->execute()) {
    echo "Besked sendt";
    echo '
        <style>.ajs-message.ajs-custom { color: #fff; background-color: #26a69a; border-color: #e0e0e0; }</style>
        <script type="text/javascript">
            $("#message-modal").modal("close");
            alertify.notify("Besked sendt!", "custom", 4, function(){console.log("dismissed");});
        </script>';
} else {
    echo "Fejl";
}

?>
<?php


$currUserId = $_POST['currUserId'];



    $dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
    $stmt = $dbh->prepare('
        DELETE FROM Messages
        WHERE msg_to_id = '.$currUserId.'
    ');

    if($stmt->execute()) {
        echo '
            <style>.ajs-message.ajs-custom { color: #fff; background-color: #26a69a; border-color: #e0e0e0; }</style>
            <script type="text/javascript">
                alertify.notify("Tømt", "custom", 4, function(){console.log("dismissed");});
            </script>';
    } else {
       echo "Fejl, noget gik galt";
    }











?>
<?php


$message_read_id = $_POST['message_read_id'];



    $dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
    $stmt = $dbh->prepare('
        UPDATE Messages
        SET msg_read = 1
        WHERE id = '.$message_read_id.'
    ');

    if($stmt->execute()) {
        echo '
            read sat til 1
            ';
    } else {
        echo "Fejl, beskeden kunne ikke slettes";
    }











?>
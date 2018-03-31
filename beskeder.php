<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

if(!$user->isLoggedIn()) {
    Redirect::to('forside');
}
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
?>

<style>
.message-h3 {
    text-align: center;
}

</style>

<div class="row">
<div class="col s2"></div>
<div class="col s8">
<h3 class="message-h3">Beskeder</h3>

<table class="highlight centered">
        <thead>
          <tr>
              <th>Fra</th>
              <th>Dato</th>
              <th>Handling</th>
          </tr>
        </thead>

        <tbody>
<?php 
$dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

// Få mængden af beskeder til brugeren
$stmt = $dbh->prepare('
    SELECT
        *
    FROM
        Messages
    WHERE msg_to_id = ' . $data->id . '
');

$stmt->execute();



function getFromName($dbh, $userid) {
    $sql = 'SELECT name FROM Users WHERE id IN (SELECT msg_from_id FROM Messages WHERE msg_from_id = '. $userid .')';
    foreach ($dbh->query($sql) as $row) {
        return $row['name'];
    }
}



if ($stmt->rowCount() > 0) {
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $iterator = new IteratorIterator($stmt);

    $x = 1;

    foreach ($iterator as $row) {
       
        $msg_date = strtotime($row['msg_date']);
        $msg_date = date("d/m/y H:i:s", $msg_date);

        echo '
        <tr>
            <input type="hidden" id="msg_from_id" value="'. $row['msg_from_id'] .'">
            <td>'. getFromName($dbh, $row['msg_from_id']) .'</td>
            <td>'. $msg_date .'</td>
            <td>
            <a class="modal-trigger" href="#message-modal'.$x.'">Vis</a>
            <div id="message-modal'.$x.'" class="modal">
                <div class="modal-content">
                <h4>Besked fra '. getFromName($dbh, $row['msg_from_id']) .'</h4>
                <p>'.$row['msg_body'].'</p>
                </div>
                <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
                </div>
            </div>
            
            </td>
        </tr>

        <script type="text/javascript">
        $(document).ready(function() {
        $("#message-modal'.$x.'").modal();
        });
        </script>
        ';
        
        $x++;
    }
} else {
    echo '<p style="text-align: center;">Du har ingen beskeder</p>';
}

?>


        </tbody>
      </table>

</div>
<div class="col s2"></div>
</div>

<?php
}

?>

<script>
$(document).ready(function() {
    // $( ".toggle-body" ).click(function() { 
    //     $('.message-body').toggle("slide", { direction: "up" }, 1000);
    // });

    
	$(".toggle-body").click(function(){
		$(this).next(".message-body").toggle()
	});

});
</script>

</main>

<?php include 'includes/components/footer.php' ?>
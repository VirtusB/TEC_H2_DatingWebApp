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

.beskeder-div {
    margin-top: 5%;
}

</style>

<div class="row">
<div class="col s2"></div>
<div class="col s8 beskeder-div">
<h3 class="message-h3">Beskeder</h3>
<p style="text-align: center;" id="deleted-success-message"></p>

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
    ORDER BY msg_date DESC
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
        <tr class="table-row-'.$row['id'].'">
            <input type="hidden" id="msg_from_id" value="'. $row['msg_from_id'] .'">
            <td>'. getFromName($dbh, $row['msg_from_id']) .'</td>
            <td>'. $msg_date .'</td>
            <td>
            <a class="modal-trigger" href="#message-modal'.$row['id'].'">Vis</a>
            <a style="margin-left:3%;" class="" id="DeleteMessage'.$row['id'].'" href="#">Slet</a>
            <input type="hidden" id="msg_id'.$row['id'].'" value="'.$row['id'].'">
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

        $("#message-modal'.$row['id'].'").modal();

        $("#DeleteMessage'.$row['id'].'").on("click", function(e) {
            e.preventDefault();
            console.log("slet besked '.$row['id'].' ");
            var msg_id = document.getElementById("msg_id'.$row['id'].'").value;

            var dataString = "message_to_delete=" + msg_id;
            $.ajax({
                type: "POST",
                url: "delete-message.php",
                data: dataString,
                cache: false,
                success: function(data) {
                  $("#deleted-success-message").html(data);
                  $(".table-row-'.$row['id'].'").fadeOut("slow");
                  alertify.notify("Besked slettet", "custom", 4, function(){console.log("dismissed");});                  
                },
                error: function(err) {
                  alert(err);
                }
              });
        });

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
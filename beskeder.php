<?php
require_once 'core/init.php';
include 'includes/components/header.php';

echo '<main>';

$dbh = new PDO('mysql:host=127.0.0.1;dbname=virtusbc_tec-dating', 'virtusbc_h2_user', 'rootpwdating', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


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

#inbox-control {
    text-align: center;
}

#inbox-message-count {
    color: #039be5;
}

.message_read {
    
}

</style>

<div class="row">
<div class="col s2"></div>
<div class="col s8 beskeder-div">
<h3 class="message-h3" id="inbox-message-count"><?php
$messageCount = $dbh->query('SELECT COUNT(msg_to_id) as msgCount FROM Messages WHERE msg_to_id = '.$data->id.' AND msg_read = 0')->fetch();
if ($messageCount['msgCount'] == 0) {
    echo '0 beskeder';
} else if ($messageCount['msgCount'] == 1) {
    echo '1 ulæst besked';
} else {
    echo $messageCount['msgCount'], ' ulæste beskeder';
}
?></h3>
<div id="inbox-control">
<button id="delete-all-messages" class="btn">Tøm indbakke</button>
<p id="ajax-response"></p>
<script>
$("#delete-all-messages").on("click", function(e){
e.preventDefault();
alertify.confirm('Tøm indbakke', 'Er du sikker?', 
function(){ 
    //alertify.success('Sletter alle beskeder');
    var currUserId = {"currUserId" : <?php echo $data->id; ?>};
    $.ajax({
        type: "POST",
        url: "empty-inbox.php",
        data: currUserId,
        cache: false,
        success: function(data) {
            $("#ajax-response").html(data);
            $("#message-table-body").fadeOut("slow");
            $("#inbox-message-count").html("0 beskeder");
        },
        error: function(err) {
            alert(err);
        }
 });

    }, 
function(){ 
    alertify.error('Annulleret')
    }).set('labels', {ok: 'Ja!', cancel: 'Annuller'});

});
</script>
</div>

<p style="text-align: center;" id="deleted-success-message"></p>

<table class="highlight centered">
        <thead>
          <tr>
              <th>Fra</th>
              <th>Dato</th>
              <th>Handling</th>
              <th>Status</th>
          </tr>
        </thead>

        <tbody id="message-table-body">
<?php 

// Få mængden af beskeder til brugeren
$stmt = $dbh->prepare('
    SELECT
        *
    FROM
        Messages
    WHERE msg_to_id = ' . $data->id . '
    ORDER BY msg_read ASC, msg_date DESC
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
        <tr class= '. (($row['msg_read'] == 1) ?'"message_read"':"") .'  class="table-row-'.$row['id'].'">
            <input type="hidden" id="msg_from_id" value="'. $row['msg_from_id'] .'">
            <td>'. getFromName($dbh, $row['msg_from_id']) .'</td>
            <td>'. $msg_date .'</td>
            <td>
            <a class="modal-trigger" href="#message-modal'.$row['id'].'">Vis</a>
            <a style="margin-left:3%;" class="" id="DeleteMessage'.$row['id'].'" href="#">Slet</a>
            <a style="margin-left:3%;" class="modal-trigger" id="RespondMessage'.$row['id'].'" href="#respond-modal'.$row['id'].'">Svar</a>            
            <input type="hidden" id="msg_id'.$row['id'].'" value="'.$row['id'].'">
            <div id="message-modal'.$row['id'].'" class="modal message-modal">
                <div class="modal-content">
                <h4>Besked fra '. getFromName($dbh, $row['msg_from_id']) .'</h4>
                <p>'.$row['msg_body'].'</p>
                </div>
                <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
                </div>
            </div>
            <div id="respond-modal'.$row['id'].'" class="modal">
                        <div class="modal-content">
                        <h4 style="text-align: center;" class="profile-message-h4-'.$row['id'].'">
                        <script>
                        $(document).ready(function() {


                        $(".profile-message-h4-'.$row['id'].'").text("Send en besked til '. getFromName($dbh, $row['msg_from_id']) .'");                                               
                        });
                        </script>
                        </h4>
                        <textarea id="profileMessageInput'.$row['id'].'" class="materialize-textarea validate" data-length="150"></textarea>

                       <p id="success-message-sent"></p>
                        </div>
                        <div class="modal-footer">
                        <!-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a> -->
                        <button id="sendTheMessage'.$row['id'].'" class="waves-effect waves-green btn-flat">Send</button>
                        </div>
                    </div>
            </td>
            <td>
            '. (($row['msg_read'] == 1) ? "Læst":"Ikke læst") .'
            </td>
        </tr>
    

        <script type="text/javascript">
        $(document).ready(function() {

        function readMessageModal(){
            $("#message-modal'.$row['id'].'").modal();
        }


        function unreadMessageModal() {
            $("#message-modal'.$row['id'].'").modal({
                complete: function() {
    
                    var msg_id = document.getElementById("msg_id'.$row['id'].'").value;
    
                    var dataString = "message_read_id=" + msg_id;
    
                    $.ajax({
                        type: "POST",
                        url: "read-message.php",
                        data: dataString,
                        cache: false,
                        success: function(data) {
                          console.log(data);
                        },
                        error: function(err) {
                          
                        }
                      });
                }
            });
        }
        

        '. (($row['msg_read'] == 1) ? "readMessageModal()":"unreadMessageModal()" ) .'
        

        
        

        $("#respond-modal'.$row['id'].'").modal();

        $("#sendTheMessage'.$row['id'].'").on("click", function(e) { 
            profileSendMessage'.$row['id'].'();
        });


        function encodeHTML(s) {
            return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
        }
        
            function profileSendMessage'.$row['id'].'() {
            var message = encodeHTML(document.getElementById("profileMessageInput'.$row['id'].'").value);
            var msg_to_id = '.$row['msg_from_id'].';
            var msg_from_id = '.$data->id.';
        
            //var dataString = "message_to_pass=" + message;
            var dataString = {
                "message_to_pass" : message,
                "message_to_user" : msg_to_id,
                "message_from_user" : msg_from_id
            };
            if (message == "") {
                alertify.alert("Fejl", "Du er nød til at indtaste en besked", function() {
                    alertify.message("OK");
                });
            } else {
              $.ajax({
                type: "POST",
                url: "pass-message.php",
                data: dataString,
                cache: false,
                success: function(data) {
                  $("#success-message-sent").html(data);
                  $("#respond-modal'.$row['id'].'").modal("close");
                },
                error: function(err) {
                  alert(err);
                }
              });
            }
            return false;
        }



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
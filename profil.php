
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



    <div class="row profile-row">

        <form method="post">
        <div class="row sex-region-interests-row">
            <div class="col s3"></div>

            <div class="col s2">
            <select name="sex_select" id="sex-select">
                <option selected="selected" disabled="disabled" value="">Køn</option>
                <option value="1">Mand</option>
                <option value="0">Kvinde</option>
            </select>
            </div>
            <div class="col s2">
            <select name="region_select" id="region-select">
                                <option value="" disabled="disabled" selected="selected">Region</option>
                                <?php
                                    $regions = DB::getInstance()->action('SELECT regionName, regionID', 'Regions', array('1', '=', '1'))->results();
                                    foreach ($regions as $region) {
                                        echo "<option value='{$region->regionID}'  >$region->regionName</option>";
                                    }
                                ?>
             </select>
            </div>
            <div class="col s2">
            <select multiple="multiple" name="interest_select[]" id="interest-select">
                                <option value="" disabled="disabled" selected="selected">Interesser</option>
                                <?php
                                    $interests = DB::getInstance()->action('SELECT interestName, interestID', 'Interests', array('1', '=', '1'))->results();
                                    foreach ($interests as $interest) {
                                        echo "<option value='{$interest->interestID}'  >$interest->interestName</option>";
                                    }
                                ?>
             </select>
            </div>

            <div class="col s3"></div>
        </div> <!-- køn, region og interesser row -->

       

        <div class="row age-slider-row valign-wrapper">
            <div class="col s3"></div>
            <div class="col s4">
            <input type="hidden" name="ageMin" value="" id="ageMin-input">
            <input type="hidden" name="ageMax" value="" id="ageMax-input">
            <div id="age-slider">                       
            <script>
               $(document).ready(function() {

                var slider = document.getElementById('age-slider');
                    noUiSlider.create(slider, {
                    start: [18, 35],
                    connect: true,
                    step: 1,
                    orientation: 'horizontal', // 'horizontal' or 'vertical'
                    range: {
                        'min': 18,
                        'max': 99
                    },
                    format: {
                        to: function ( value ) {
                            return value.toFixed(0);
                        },
                        from: function ( value ) {
                          return value.replace(',-', '');
                        }
                      }
                });

                    var ageslider = document.getElementById("age-slider");

                    var ageMin = document.getElementById("ageMin-input");
                    var ageMax = document.getElementById("ageMax-input");

                    ageslider.noUiSlider.on('update', function(values, handle) {
                        ageMin.value = values[0];
                        ageMax.value = values[1];
                    });
                });
            </script>
            </div>                     
            </div>
            <div class="col s2">
                <button class="btn filter-search-btn">Filtrer</button>
            </div>
            <div class="col s3"></div>
        </div>

        </form>

        <div class="divider profile-divider"></div>
       <div class="row view-profiles valign-wrapper center-align">
       <div class="col s1">
       <a id="previous-link" href="">
        <i id="previous-profile-btn" title="Forrige profil" class="fa fa-arrow-left profile-arrows"></i>
        </a>
        </div>

        <div class="col s10">
        <div class="row profile-name-age-row">
            <div class="col s2">
            <a id="dislike-link" href="">
            <i id="dislike-profile-btn" title="Synes ikke godt om" class="fa fa-thumbs-down profile-thumbs"></i>
            <script>
            $(document).ready(function() {
                //var link = $("#next-link").attr("href");
                //var id = link.replace(/[^0-9]/g,'');
                //console.log(id);
                $.ajax({
                        url:"fetch-profile.php",
                        method:"POST",
                        dataType:'json',
                        //data: {type:standard},
                        success:function(data) {
                            //console.log(data);
                            //console.table(data);
                            var profileCount = data.Users.length;
                            $(".name-paragraph").text(data.Users[0].name + ', ' + data.Users[0].age + ' år');  
                            $(".profile-image").attr("src", `data:image/jpeg;base64,${data.Users[0].imageFile}`);  
                            $("#user-since").text(`Bruger siden ${data.Users[0].joined}`);                        
                            var i = 0;
                            
                            $("#next-profile-btn").on("click", function(event) {
                                event.preventDefault();
                                i++;
                                if(i > profileCount - 1) {
                                    alert("ikke flere profiler");
                                    return false;
                                } else {
                                    $(".name-paragraph").text(data.Users[i].name + ', ' + data.Users[i].age + ' år');
                                    $(".profile-image").attr("src", `data:image/jpeg;base64,${data.Users[i].imageFile}`);
                                    $("#user-since").text(`Bruger siden ${data.Users[i].joined}`);
                                }
                            });
                            
                            $("#previous-profile-btn").on("click", function(event) {
                                event.preventDefault();
                                i--;
                                if (i < 0) {
                                    i++;
                                    alert("ikke flere profiler");
                                    return false;
                                } else {
                                    $(".name-paragraph").text(data.Users[i].name + ', ' + data.Users[i].age + ' år'); 
                                    $(".profile-image").attr("src", `data:image/jpeg;base64,${data.Users[i].imageFile}`);
                                    $("#user-since").text(`Bruger siden ${data.Users[i].joined}`);                                  
                                }
                            });


                        }
                    });

               
            });
            
            </script>
            </a>
            </div>
            <div class="col s8">
            <p class="name-paragraph"></p>            
            </div>
            <div class="col s2">
            <a id="like-link" href="">
            <i id="like-profile-btn" title="Synes godt om" class="fa fa-thumbs-up profile-thumbs"></i>
            </a>
            </div>
        </div>
        <div class="row">
            <div class="col s6 profile-image-row">
                <img class="profile-image">
            </div>
            <div class="col s6 profile-info-row">
              <div class="profile-info">
                <p id="user-since">
                
                </p>
                <div class="divider"></div>
                <p class="profile-bio">

                </p>
                
                <!-- "Chat med" knap -->
                <div class="message-user">
                    <div class="divider"></div>
                    <!-- <button class="btn message-user-btn">Chat med </button> -->
                    <a class="message-user-btn modal-trigger btn" href="#message-modal">Chat med </a>
                    <input type="hidden" name="user-id-to-message" id="user-id-to-message"> <!-- dette input indeholder ID'et på den bruger som man klikker "Chat med" -->
                    <input value="<?php echo $data->id; ?>" type="hidden" name="user-id-from-message" id="user-id-from-message"> <!-- dette input indeholder ID'et på den bruger som er logget ind --> 
                    <!-- "Chat med" popup -->
                    <div id="message-modal" class="modal">
                        <div class="modal-content">
                        <h4 class="profile-message-h4">
                        <script>
                        $(document).ready(function() {


                        var nameParagraph = $(".name-paragraph").text();
                        nameParagraph = nameParagraph.split(","); 
                        $(".profile-message-h4").text("Send en besked til " + nameParagraph[0])                                               
                        });
                        </script>
                        </h4>
                        <textarea id="profileMessageInput" class="materialize-textarea validate" data-length="150"></textarea>
                        <p id="success-message"></p>
                        <script>
                        function encodeHTML(s) {
                            return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
                        }

                        function profileSendMessage() {
                            var message = encodeHTML(document.getElementById("profileMessageInput").value);
                            var msg_to_id = document.getElementById("user-id-to-message").value;
                            var msg_from_id = document.getElementById("user-id-from-message").value;

                            //var dataString = "message_to_pass=" + message;
                            var dataString = {
                                "message_to_pass" : message,
                                "message_to_user" : msg_to_id,
                                "message_from_user" : msg_from_id
                            };
                            if (message == "") {
                                alertify.alert('Fejl', "Du er nød til at indtaste en besked", function() {
                                    alertify.message("OK");
                                });
                            } else {
                              $.ajax({
                                type: "POST",
                                url: "pass-message.php",
                                data: dataString,
                                cache: false,
                                success: function(data) {
                                  $("#success-message").html(data);
                                },
                                error: function(err) {
                                  alert(err);
                                }
                              });
                            }
                            return false;
                          }
                        </script>
                        </div>
                        <div class="modal-footer">
                        <!-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a> -->
                        <button onclick="profileSendMessage();" class="waves-effect waves-green btn-flat">Send</button>
                        </div>
                    </div>
                </div>
                
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col s6 profile-interests">
                <div class="interests-div">
                <h6 class="user-interests-h6">'s interesser</h6>
                <div class="divider interests-divider"></div>
                <ul class="interests-ul">

                </ul>
                </div>
             
            </div>
            <div class="col s6 profile-location">
            <div class="location-div">
            <h6 class="user-location-h6">'s placering</h6>
            <div class="divider"></div>
            <div class="inner-location-div">
             <p id="user-city"></p>
             <p id="user-country-region">, </p>
            </div>
            </div>
            </div>
        </div>
        </div>

        <div class="col s1">
        <a id="next-link" href="">
        <i id="next-profile-btn" title="Næste profil" class="fa fa-arrow-right profile-arrows"></i>
        </a>
        </div>
       </div>
        



    </div> <!-- profile row -->

    <div id="ajax-profile-data">

    </div>

    </div>

    <!-- <h3><?php //echo escape($data->username) ?></h3>
    <p>Fulde navn: <?php //echo escape($data->name) ?></p>
    <img class="profile-image" src="data:image/jpeg;base64,<?php //echo escape($data->imageFile);?>" /> -->

    <?php
}

?>

</main>

<?php include 'includes/components/footer.php' ?>

<script>


$(document).ready(function() {

$(document).ready(function(){
    $("#message-modal").modal();
  });


// If cookie is set, scroll to the position saved in the cookie.
if ( Cookies.get('scroll') !== null ) {
    $(document).scrollTop( Cookies.get('scroll') );
}

// When a button is clicked...
$('#next-link, #prev-link, .filter-search-btn').on("click", function() {
    // Set a cookie that holds the scroll position.
    Cookies.set("scroll", $(document).scrollTop())
});

});
</script>

</body>
</html>
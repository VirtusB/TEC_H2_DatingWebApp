<?php
require_once 'core/init.php';
include 'includes/components/header.php';


    
if (!empty($_POST["contact-firstname"]) && !empty($_POST["contact-lastname"]) && !empty($_POST["contact-email"]) && !empty($_POST["contact-message"])) {
    $EmailSender = new EmailSender();
    //$EmailSender->contactFormSend();
    if($EmailSender->contactFormSend()) {
        Session::flash('home', 'Din besked blev sendt');
        Redirect::to('forside');
    } else {
        Session::flash('home', 'Fejl. Prøv at sende mailen manuelt til admin@dating.virtusb.com');
        Redirect::to('forside');
    }
}

echo '<main>';
?>

<div class="container">
    <div class="row">
        <div class="col m10 offset-m1 s12">
            <h2 style="color: #535151" class="center-align">Hvad kan vi hjælpe med?</h2>
            <div class="row">
                <form method="post" id="contact-form" class="col s12">
                    <div class="row">
                        <div class="input-field col m6 s12">
                            <input id="first_name" name="contact-firstname" type="text" class="validate">
                            <label for="first_name">Fornavn</label>
                        </div>
                        <div class="input-field col m6 s12">
                            <input id="last_name" name="contact-lastname" type="text" class="validate">
                            <label for="last_name">Efternavn</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m6 s12">
                            <i class="mdi-content-mail prefix"></i>
                            <input id="email" name="contact-email" type="text" class="validate">
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field col m6 s12">
                            <i class="mdi-maps-store-mall-directory prefix"></i>
                            <input id="phone_number" name="contact-phone" type="tel" class="validate">
                            <label for="phone_number">Telefon nr.</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                          <textarea id="message" name="contact-message" class="materialize-textarea"></textarea>
                          <label for="message">Besked</label>
                        </div>
                    </div>

                   

                    <div class="row">
                        <div class="input-field col m6 s12">
                            <i class="mdi-maps-store-mall-directory prefix"></i>
                            <input id="vertification" type="text" class="validate">
                            <label for="vertification">Hvad er 2 +2?</label>
                        </div>
                        <div class="col m6">
                         <p class="right-align"><button id="contact-submit" class="btn btn-large waves-effect waves-light" type="submit" name="contact-submit">Send Besked</button></p>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


 </main>

<?php include 'includes/components/footer.php'; ?>
<script>
$(document).ready(function() {
  $("#contact-submit").on("click", function() {
    errorMessage = "";
    var contactFormValid = true;
    if ($("#first_name").val() == "") {
      errorMessage = "Du er nød til at indtaste et fornavn";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#last_name').val() == "") {
      errorMessage = "Du er nød til at indtaste et efternavn";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#phone_number').val() == "") {
      errorMessage = "Du er nød til at indtaste et telefon nr.";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#email').val() == "") {
      errorMessage = "Du er nød til at indtaste en email";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } else if(/\S+@\S+\.\S+/.test($('#email').val()) == false) {
      errorMessage = "Du er nød til at indtaste en korrekt email adresse";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#message').val() == "") {
      errorMessage = "Du er nød til at indtaste en besked";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } else if($('#vertification').val() != 4) {
      errorMessage = "Svaret givet til 2 + 2 er forkert";
      contactFormValid = false;
      $("#contact-form").submit(function(e) {
        e.preventDefault();
      });
    } 
    if (!contactFormValid && errorMessage.length > 0) {
      alertify.alert('Fejl', errorMessage, function() {
        alertify.message("OK");
      });
    } else {
      $("#contact-form")
        .unbind("submit")
        .submit();
    }
  });
});
</script>

</body>
</html>
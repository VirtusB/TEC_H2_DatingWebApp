// initialiser html-select
$(document).ready(function() {
  $("#theme-select").material_select();
});
// initialiser html-select
$(document).ready(function() {
  $("#region-select").material_select();
});
// initialiser html-select
$(document).ready(function() {
  $("#sex-select").material_select();
});
// initialiser html-select
$(document).ready(function() {
  $("#country-select").material_select();
});
// initialiser date picker
$('.birthday-picker-input').pickadate({
  monthsFull: ['Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December'],
  weekdaysShort: ['Man', 'Tir', 'Ons', 'Tor', 'Fre', 'Lør', 'Søn'],
  formatSubmit: 'yyyy/dd/mm',
  selectMonths: true, // Creates a dropdown to control month
  selectYears: 15, // Creates a dropdown of 15 years to control year,
  today: 'I dag',
  clear: 'Slet',
  close: 'Ok',
  closeOnSelect: false // Close upon selecting a date,
});

// fejl skal fades out, fade out skal begynde efter 4 sekunder og tage 1600 ms
$(document).ready(function() {
  setTimeout(function() {
    $(".div-validation-errors").fadeOut(1600, function() {});
  }, 4000);
});

// test af jquery/javascript validation, kun sat til på username
// brug af custom alerts fra alertify.js
$(document).ready(function() {
  $(".signup-btn").on("click", function() {
    errorMessage = "";
    var signupFormValid = true;
    if ($("#username").val() == "") {
      errorMessage = "Du er nød til at indtaste et brugernavn";
      signupFormValid = false;
      $("#signup-form").submit(function(e) {
        e.preventDefault();
      });
    }
    if (!signupFormValid && errorMessage.length > 0) {
      alertify.alert('Fejl', errorMessage, function() {
        alertify.message("OK");
      });
    } else {
      $("#signup-form")
        .unbind("submit")
        .submit();
    }
  });
});

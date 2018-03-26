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

$(document).ready(function() {
  $("#interest-select").material_select();
});


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
   format: wNumb({
     decimals: 0
   })
  });





// initialiser date picker
$('.birthday-picker-input').pickadate({
  monthsFull: ['Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December'],
  weekdaysShort: ['Man', 'Tir', 'Ons', 'Tor', 'Fre', 'Lør', 'Søn'],
  formatSubmit: 'yyyy/dd/mm',
  selectMonths: true, // Creates a dropdown to control month
  selectYears: 100, // Creates a dropdown of 15 years to control year,
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



// ubrugt
// skulle bruges til at loade brugerns billede igen, under oprettelse, ikke muligt
document.addEventListener('DOMContentLoaded', function() {
  //$('#proimg').val(localStorage.getItem('profileImg'));

  //var imgStorage = localStorage.getItem('profileImg');

  if(localStorage.getItem("profileImg") != null) {
    //$('#profile-image').attr('src', localStorage.getItem('profileImg'));
  } 
  
});

// load billede med js
function readURL(input) {

  if (input.files && input.files[0]) {


    var size = input.files[0].size;
    if (size < 5000) {
      $(input).val("");
      size = size/1000;
      size = size.toFixed(2);
      alertify.alert('Fejl', `Billedet skal mindst være 5 KB, det valgte billede fylder ${size}KB`, function() {
        alertify.message("OK");
      });
    } else if (size > 1000000) {
      $(input).val("");
      size = size/1000000;
      size = size.toFixed(2);
      alertify.alert('Fejl', `Billedet er for stort, maks 1 MB, det valgte billede fylder ${size}MB`, function() {
        alertify.message("OK");
      });
    } else {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#profile-image').attr('src', e.target.result);
        //localStorage.setItem('profileImg', e.target.result);
      }
  
      reader.readAsDataURL(input.files[0]);
    }

    
  }
}




$(document).ready(function() {
  $("#img_input").change(function() {
    readURL(this);
  });
});














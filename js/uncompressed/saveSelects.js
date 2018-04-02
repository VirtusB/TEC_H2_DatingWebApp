



// lav en cookie med profil beskrivelsens værdi og sæt profil beskrivelsens værdi til denne værdi
// nødvendigt da value="" ikke kunne sættes med php
$(document).ready(function() {
    $("#bio_input").val(Cookies.get('bio_input_cookie'));
   
   $("#bio_input").change(function() {
     Cookies.set('bio_input_cookie', $("#bio_input").val());
   });
   
   });




// gem køn select som cookie
var saveclass = null;
function saveSelected(cookieValue, select, cookie)
{
    var sel = document.getElementById(select);

    saveclass = saveclass ? saveclass : document.body.className;
    document.body.className = saveclass + ' ' + sel.value;

    setCookie(cookie, cookieValue, 365);
}

function setCookie(cookieName, cookieValue, nDays) {
    var today = new Date();
    var expire = new Date();

    if (nDays==null || nDays==0)
        nDays=1;

    expire.setTime(today.getTime() + 3600000*24*nDays);
    document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString();
}

// læser cookie
function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function selectEvent(select, cookie) {
    var themeSelect = document.getElementById(select);
    var selectedTheme = readCookie(cookie);

    themeSelect.value = selectedTheme;
    saveclass = saveclass ? saveclass : document.body.className;
    document.body.className = saveclass + ' ' + selectedTheme;
}

if (document.location.pathname.match(/\opret/)) {
selectEvent('sex-select', 'sexVal');
selectEvent('region-select', 'regionVal');
selectEvent('country-select', 'countryVal'); 
}



// loader cookien
// document.addEventListener('DOMContentLoaded', function() {
//   var themeSelect = document.getElementById('sex-select');
//   var selectedTheme = readCookie('theme');

//   themeSelect.value = selectedTheme;
//   saveclass = saveclass ? saveclass : document.body.className;
//   document.body.className = saveclass + ' ' + selectedTheme;
// });
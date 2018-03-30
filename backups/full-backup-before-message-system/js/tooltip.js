$( "#previous-profile-btn" ).tooltip({
    tooltipClass: "previous-profile-tooltip",
    track: true,
    position: { my: "left top+15", at: "right bottom", collision: "none" }
});

$(document).ready(function() {
    $( "#next-profile-btn" ).tooltip({
        tooltipClass: "next-profile-tooltip",
        track: true,
        position: { my: "left top+15", at: "left bottom", collision: "none" }
    });
});

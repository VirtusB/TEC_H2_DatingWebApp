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

$( "#like-profile-btn" ).tooltip({
    tooltipClass: "dislike-profile-tooltip",
    track: true,
    position: { my: "left top+15", at: "right bottom", collision: "none" }
});

$( "#dislike-profile-btn" ).tooltip({
    tooltipClass: "like-profile-tooltip",
    track: true,
    position: { my: "left top+15", at: "right bottom", collision: "none" }
});

$( "#age-slider" ).tooltip({
    tooltipClass: "age-slider-tooltip",
    track: true,
    position: { my: "left top+15", at: "right bottom", collision: "none" }
});
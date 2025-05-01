$(document).ready(function() {
    var flipbook = $("#flipbook");

    // Initialize flipbook
    flipbook.turn({
        width: 600,
        height: 400,
        autoCenter: true
    });

    // Next/Previous button functionality
    $("#next").click(function() {
        flipbook.turn("next");
    });

    $("#prev").click(function() {
        flipbook.turn("previous");
    });
});


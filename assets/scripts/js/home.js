$(document).ready(function () {

    // Set margin top of carousel
    var navbarHeight = -($(".navbar").outerHeight());
    $("#featured-items").css("margin-top", navbarHeight);

    // Set height of carousel
    $(".carousel-item").height($(window).height());

    // Set width of carousel bg
    $(".slide-bg").width($(window).width());
});
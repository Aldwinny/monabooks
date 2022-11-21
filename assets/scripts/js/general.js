// Change navbar appearance
$(window).scroll(function () {
    if($("body").scrollTop() > 150 || $(document.documentElement).scrollTop() > 150) {
        $("#default-nav").removeClass("custom-navbar");
    } else {
        $("#default-nav").addClass("custom-navbar");
    }
});

// Slide down dropdown menu
$(".dropdown").mouseover(function () {
    $(this).children(".dropdown-menu").stop(true, true).slideDown(400);
});
$(".dropdown").mouseleave(function () {
    $(this).children(".dropdown-menu").stop(true, true).slideUp(400);
});
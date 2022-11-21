$(".form-group > [input-field]")
    .focus(function() {
        $(this).parent().children(".form-label").css("transform", "translateX(2rem)");
    })
    .blur(function() {
        $(this).parent().children(".form-label").css("transform", "");
    });
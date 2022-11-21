$("[hidden-toggle]").click(function(e) {
    $("[hidden]").removeAttr("hidden");
    $(".main").hide();
    e.preventDefault();
});
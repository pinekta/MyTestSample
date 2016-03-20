$(function () {
    $(".close").bind("click", function () {
        $(this).parent("div").parent("div").addClass("hide", 1000, "linear");
    });
});

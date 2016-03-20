$(function () {
    $(".password-input").addClass("hide");
    $("#button-password-change").addClass("hide");

    $("#button-password-display").bind("click", function () {
        $("#password-mask").text($("#non-mask-password").val());
        $(this).addClass("hide");
        $("#button-password-change").removeClass("hide");
    });
    $("#button-password-change").bind("click", function () {
        $(".password-input").css("padding-left", "15px");
        $(".password-input").removeClass("hide");
        $(".password-label").addClass("hide");
        $(this).addClass("hide");
    });
});

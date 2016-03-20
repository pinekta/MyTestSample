$(function () {
    $("#button-password-display").bind("click", function () {
        $("#password-mask").text($("#non-mask-password").val());
        $(this).addClass("hide");
    });
    $("#back").bind("click", function () {
        $("form").attr("method", "GET");
        $("form").attr("action", $("#click-back-url").val());
    });
});

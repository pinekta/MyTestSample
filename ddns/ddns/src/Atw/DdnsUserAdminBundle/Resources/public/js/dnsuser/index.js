$(function () {
    $(".btn-danger").bind("click", function () {
        if (!confirm("選択したユーザを削除してもよろしいでしょうか？")) {
            return false;
        }
    });

    var clipboard = new Clipboard('.is-clippable', {
        target: function(trigger) {
            return trigger;
        }
    });
    clipboard.on('success', function(e) {
        $.notify({
            icon: "pe-7s-bell",
            message: "<b>クリップボードにコピーしました。</b><br>" + e.text
        },{
            type: 'info',
            timer: 300,
            placement: {
                from: "top",
                align: "right"
            }
        });
        e.clearSelection();
    });
});

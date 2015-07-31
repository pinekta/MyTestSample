/**
 * Create Notification
 * @param string functionName
 * @param string title
 * @param string description
 * @param string methodsynopsis
 */
var createNotification = function (functionName, title, description, methodsynopsis) {
    var option = {
        type: "basic",
        title: title,
        message: description + "\r" + methodsynopsis,
        iconUrl: "img/logo.gif"
    };
    var notifications = chrome.notifications;
    notifications.create(title, option, function () {});
    notifications.onClicked.addListener(function () {
        window.open("http://php.net/manual/ja/" + functionName + ".php", functionName);
    });
}

/**
 * Notify
 */
var notify = function () {
    var functionName = urlList[Math.floor(Math.random() * urlList.length)];
    $.ajax({
        url: "http://php.net/manual/ja/" + functionName + ".php",
        type: "GET",
        success: function(data) {
            var title = $(data).find('h1').text();
            var description = $(data).find('p.refpurpose').text();
            var methodsynopsis = $(data).find('div.methodsynopsis').text().replace(/[\r\n]/g, "");
            createNotification(functionName, title, description, methodsynopsis);
        },
        error: function() {
            createNotification(functionName, "Error!!!", "Error:", "The URL is invallid.");
        }
    });
};

notify();
var minuteInterval = 5 * (60 * 1000);
var timer = setInterval(notify , minuteInterval);

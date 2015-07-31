chrome.app.runtime.onLaunched.addListener(function() {
  chrome.app.window.create('window.html', {
    'outerBounds': {
      'width': 400,
      'height': 500
    }
  });

  // getDevices でデバイスを探す
  function onDeviceFound(devices) {
      this.devices = devices;
      if (devices) {
          if (devices.length > 0) {
              console.log("Device found: "+devices.length);
          } else {
              console.log("Device not found");
          }
      } else {
          console.log("Permission denied.");
      }
  }

  chrome.usb.getDevices({"vendorId": vendorId, "productId": productId}, onDeviceFound);

  // openDeviceでデバイスと接続
  var usbConnection = null;
  var onOpenCallback = function(connenction) {
      if (connection) {
          usbConnection = connection;
          console.log("Device opened.");
      } else {
          console.log("Device failed to open.");
      }
  };

  chrome.usb.openDevice(device, onOpenCallback);

  // bulkTransger バルク転送でデータを受け取る
  var onTransferCallback = function(event) {
      if (event && event.resultCode === 0 && event.data) {
          console.log("got " + event.data.byteLenght + " bytes");
      }
  };

  chrome.usb.bulkTransfer(connectionHandle, transferInfo, onTransferCallback);
});

<!DOCTYPE html>
<html>

<head>
    <title>Payment Completed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        window.onload = function () {
            if (window.AndroidInterface && window.AndroidInterface.postMessage) {
                window.AndroidInterface.postMessage('paymentSuccess');
            } else if (window.ReactNativeWebView) {
                window.ReactNativeWebView.postMessage('paymentSuccess');
            } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.nativeApp) {
                window.webkit.messageHandlers.nativeApp.postMessage('paymentSuccess');
            }
            setTimeout(function () {
                window.close();
            }, 500);
        };
    </script>
</head>

<body>
    <p style="text-align:center; margin-top: 50px;">Action Finished, You Can Close This Page</p>
</body>

</html>
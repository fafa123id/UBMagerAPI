<!DOCTYPE html>
<html>
<head>
    <title>Payment Completed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Detect if in Android WebView
        function isAndroidApp() {
            return typeof AndroidInterface !== 'undefined';
        }

        // Detect if in iOS WebView (via injected JS bridge, opsional)
        function isIOSApp() {
            return window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.closeWindow;
        }

        window.onload = function () {
            // Try close WebView via deep linking
            if (isAndroidApp()) {
                AndroidInterface.close(); // Panggil method di WebView Android
            } else if (isIOSApp()) {
                window.webkit.messageHandlers.closeWindow.postMessage(null); // iOS bridge
            } else {
                // Fallback: coba auto close tab
                window.close();
            }
        };
    </script>
</head>
<body>
    <p style="text-align:center; margin-top: 50px;">Thank you! Finishing payment...</p>
</body>
</html>

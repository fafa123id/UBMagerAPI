   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>Payment Finished</title>
       <script>
           // Jika menggunakan WebView di mobile app (Android/iOS),
           // biasanya ada metode khusus untuk close WebView misal:
           // - Di Android WebView: window.AndroidInterface.close(); (jika sudah disediakan)
           // - Di iOS WKWebView: gunakan postMessage ke native code

           // Contoh fallback: tutup tab/window browser (jika bisa)
           window.onload = function() {
               // Kirim pesan ke native app jika perlu
               if (window.ReactNativeWebView) {
                   // untuk React Native WebView
                   window.ReactNativeWebView.postMessage('paymentSuccess');
               } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.nativeApp) {
                   // untuk iOS WKWebView (Swift)
                   window.webkit.messageHandlers.nativeApp.postMessage('paymentSuccess');
               } else if (window.AndroidInterface && window.AndroidInterface.close) {
                   // untuk Android WebView Bridge
                   window.AndroidInterface.close();
               } else {
                   // fallback: coba tutup jendela (hanya jika di browser)
                   window.open('', '_self').close();
               }
           };
       </script>
   </head>
   <body>
       <h3>Payment successful. You can close this page.</h3>
   </body>
   </html>
   
<!-- <link rel="manifest" href="<?php echo base_url('assets/manifest.json'); ?>">
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PWA</title>
    <link rel="manifest" href="../public/manifest.json">
    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const installButton = document.getElementById('installButton');
            installButton.style.display = 'block';

            installButton.addEventListener('click', (e) => {
                installButton.style.display = 'none';
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });
    </script>
</head>
<body>
    <h1>Selamat Datang di Aplikasi PWA</h1>
    <button id="installButton" style="display:none;">Install App</button>
</body>
</html>

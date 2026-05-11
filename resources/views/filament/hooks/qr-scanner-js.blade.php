<script src="{{ asset('html5-qrcode/html5-qrcode.min.js') }}"></script>
<script>
    window.qrScannerInitialized = true;

    let html5QrCode = null;
    let scanningActive = false;

    window.startScanner = function () {
        const btn = document.getElementById('scan-qr-btn');
        const stopBtn = document.getElementById('stop-scan-btn');
        const reader = document.getElementById('qr-reader');
        if (!btn || !stopBtn || !reader) return;

        btn.style.display = 'none';
        stopBtn.style.display = 'inline-flex';
        reader.style.display = 'block';
        reader.innerHTML = '';

        try {
            html5QrCode = new Html5Qrcode("qr-reader");
        } catch (e) {
            alert('Library QR tidak tersedia. Refresh halaman.');
            window.stopScanner();
            return;
        }

        Html5Qrcode.getCameras().then(function (cameras) {
            if (!cameras || !cameras.length) {
                alert('Kamera tidak tersedia');
                window.stopScanner();
                return;
            }
            var envCam = cameras.find(function(c) {
                return c.label.toLowerCase().includes('back') || c.label.toLowerCase().includes('environment');
            });
            var cameraId = envCam ? envCam.id : cameras[0].id;

            return html5QrCode.start(
                cameraId,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                function () {}
            );
        }).then(function () {
            scanningActive = true;
        }).catch(function (err) {
            console.error(err);
            try { html5QrCode.stop(); } catch (e) {}
            alert('Tidak dapat mengakses kamera.\n' + (err.message || 'Periksa izin kamera.'));
            window.stopScanner();
        });
    };

    window.stopScanner = function () {
        scanningActive = false;
        if (html5QrCode) {
            try { html5QrCode.stop(); } catch (e) {}
            try { html5QrCode.clear(); } catch (e) {}
            html5QrCode = null;
        }
        var btn = document.getElementById('scan-qr-btn');
        var stopBtn = document.getElementById('stop-scan-btn');
        var reader = document.getElementById('qr-reader');
        var resultDiv = document.getElementById('qr-result');
        if (btn) btn.style.display = 'inline-flex';
        if (stopBtn) stopBtn.style.display = 'none';
        if (reader) { reader.style.display = 'none'; reader.innerHTML = ''; }
        if (resultDiv) { resultDiv.classList.add('hidden'); resultDiv.innerHTML = ''; }
    };

    function onScanSuccess(decodedText) {
        if (!scanningActive) return;
        scanningActive = false;

        try { html5QrCode.pause(); } catch (e) {}

        var btn = document.getElementById('scan-qr-btn');
        var stopBtn = document.getElementById('stop-scan-btn');
        var reader = document.getElementById('qr-reader');
        var resultDiv = document.getElementById('qr-result');

        if (btn) btn.style.display = 'none';
        if (stopBtn) stopBtn.style.display = 'none';
        if (reader) reader.style.display = 'none';

        if (resultDiv) {
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = '<p class="text-sm text-gray-500">Mencari data...</p>';
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('qr-scan.search') }}', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.onload = function () {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data.found) {
                    if (resultDiv) {
                        resultDiv.innerHTML = '<p class="text-sm text-green-600">Ditemukan! Mengarahkan...</p>';
                    }
                    setTimeout(function () {
                        window.location.href = data.editUrl;
                    }, 500);
                } else {
                    if (resultDiv) {
                        resultDiv.innerHTML = '<p class="text-sm text-danger-600">' + (data.message || 'Record tidak ditemukan') + '</p>';
                    }
                    if (btn) { btn.style.display = 'inline-flex'; }
                }
            } catch (e) {
                if (resultDiv) {
                    resultDiv.innerHTML = '<p class="text-sm text-danger-600">Gagal memproses respons</p>';
                }
                if (btn) btn.style.display = 'inline-flex';
            }
        };
        xhr.onerror = function () {
            if (resultDiv) {
                resultDiv.innerHTML = '<p class="text-sm text-danger-600">Gagal mencari data</p>';
            }
            var btn2 = document.getElementById('scan-qr-btn');
            if (btn2) btn2.style.display = 'inline-flex';
        };
        xhr.send(JSON.stringify({ code: decodedText }));

        try { html5QrCode.stop(); } catch (e) {}
        try { html5QrCode.clear(); } catch (e) {}
        html5QrCode = null;
    }
</script>

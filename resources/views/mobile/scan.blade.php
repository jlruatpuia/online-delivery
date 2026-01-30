@extends('mobile.layout')

@section('title', 'Scan Invoice')

@section('content')

    <h6 class="fw-bold mb-3">Scan Invoice QR / Barcode</h6>

    <div id="qr-reader" style="width:100%"></div>

    <form method="POST"
          action="{{ route('mobile.scan.handle') }}"
          id="scanForm">
        @csrf
        <input type="hidden" name="scan_result" id="scanResult">
    </form>

    <p class="text-muted text-center mt-3">
        Align the code inside the box
    </p>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let scanned = false;

        // 🔊 Beep sound (base64 – no file needed)
        const beepAudio = new Audio(
            "data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEAIlYAAESsAAACABAAZGF0YQAAAAA="
        );

        // 📳 Vibration helper
        function vibrate() {
            if (navigator.vibrate) {
                navigator.vibrate(200);
            }
        }

        const scanner = new Html5Qrcode("qr-reader");

        scanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            (decodedText) => {
                if (scanned) return; // prevent double scan
                scanned = true;

                // 🔊 Beep
                beepAudio.play().catch(() => {});

                // 📳 Vibrate
                vibrate();

                // Stop camera
                scanner.stop().then(() => {
                    document.getElementById('scanResult').value = decodedText;
                    document.getElementById('scanForm').submit();
                });
            },
            (error) => {
                // ignore scan errors
            }
        );
    </script>

@endsection

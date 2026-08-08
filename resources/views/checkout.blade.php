<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Belajar Midtrans Sandbox</title>
    {{-- Perhatikan: script ini pakai domain SANDBOX, bukan production --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 60px auto; }
        input { display: block; width: 100%; padding: 8px; margin-bottom: 12px; }
        button { padding: 10px 20px; cursor: pointer; }
        #status { margin-top: 16px; font-weight: bold; }
    </style>
</head>
<body>

    <h2>{{ $product['name'] }}</h2>
    <p>Harga: Rp {{ number_format($product['price'], 0, ',', '.') }}</p>

    <input type="text" id="name" placeholder="Nama Anda" value="Peserta Lomba">
    <input type="email" id="email" placeholder="Email Anda" value="[email protected]">

    <button id="pay-button">Bayar Sekarang</button>
    <p id="status"></p>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const statusEl = document.getElementById('status');

            statusEl.innerText = 'Memproses...';

            fetch('/checkout/pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, email })
            })
            .then(response => response.json())
            .then(data => {
                statusEl.innerText = '';
                // snap.pay() akan membuka popup pembayaran sandbox Midtrans
                snap.pay(data.snap_token, {
                    onSuccess: function () {
                        statusEl.innerText = 'Pembayaran berhasil!';
                    },
                    onPending: function () {
                        statusEl.innerText = 'Menunggu pembayaran...';
                    },
                    onError: function () {
                        statusEl.innerText = 'Terjadi kesalahan saat pembayaran.';
                    },
                    onClose: function () {
                        statusEl.innerText = 'Anda menutup popup sebelum menyelesaikan pembayaran.';
                    }
                });
            })
            .catch(() => {
                statusEl.innerText = 'Gagal menghubungi server.';
            });
        });
    </script>

</body>
</html>

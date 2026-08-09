<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans Checkout</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style>
        :root {
            --bg: #f3f6ff;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #64748b;
            --primary: #4f46e5;
            --primary-soft: #eef2ff;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, sans-serif;
            background: linear-gradient(180deg, #eef2ff 0%, #f8fafc 100%);
            color: var(--text);
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .container {
            width: min(960px, 100%);
            display: grid;
            gap: 24px;
            grid-template-columns: 1.3fr 1fr;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.08);
        }

        .hero {
            display: grid;
            gap: 16px;
            max-width: 540px;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.05;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            line-height: 1.75;
        }

        .card {
            background: var(--primary-soft);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(79, 70, 229, 0.15);
        }

        .product-title {
            margin: 0 0 10px;
            font-size: 1.15rem;
        }

        .product-price {
            margin: 0;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.8rem;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 1rem;
            color: var(--text);
            background: #fff;
            outline: none;
        }

        input:focus {
            border-color: rgba(79, 70, 229, 0.4);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 16px;
            border: none;
            border-radius: 16px;
            background: var(--primary);
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 150ms ease, background 150ms ease;
        }

        button:hover {
            transform: translateY(-1px);
            background: #3730a3;
        }

        button:active {
            transform: translateY(0);
        }

        .status {
            margin-top: 18px;
            color: var(--muted);
            min-height: 24px;
        }

        .support {
            margin-top: 32px;
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.7;
        }

        .sidebar {
            display: grid;
            gap: 18px;
        }

        .sidebar h2 {
            margin: 0;
            font-size: 1.15rem;
        }

        .note {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px;
            line-height: 1.8;
        }

        .note strong {
            display: block;
            margin-bottom: 10px;
        }

        @media (max-width: 860px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="container">
            <section class="panel hero">
                <small>Midtrans Sandbox</small>
                <h1>Beli produk dengan pembayaran online</h1>
                <p>Ini tampilan checkout sederhana namun lebih modern. Cukup isi nama dan email, lalu klik tombol bayar untuk mencoba popup Midtrans sandbox.</p>

                <div class="card">
                    <p class="product-title">{{ $product['name'] }}</p>
                    <p class="product-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                </div>

                <label for="name">Nama pembeli</label>
                <input type="text" id="name" placeholder="Nama Anda" value="Peserta Lomba">

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Email Anda" value="peserta@example.com">

                <button id="pay-button">Bayar Sekarang</button>
                <div id="status" class="status"></div>

                <div class="support">
                    <strong>Catatan:</strong>
                    Gunakan mode sandbox Midtrans. Transaksi di sini hanya untuk percobaan, bukan pembayaran nyata.
                </div>
            </section>

            <aside class="sidebar">
                <div class="note">
                    <strong>Info singkat</strong>
                    <p>Anda sudah menggunakan Midtrans sandbox dengan Client Key dan Server Key di file <code>.env</code>.</p>
                    <p>Setelah klik bayar, sistem akan membuat order pending dan memanggil API Midtrans untuk mendapatkan token pembayaran.</p>
                </div>
                <div class="note">
                    <strong>Langkah berikutnya</strong>
                    <p>Untuk membuat tampilan lebih seperti dashboard shop, kita bisa tambah daftar produk, keranjang, dan status order.</p>
                </div>
            </aside>
        </div>
    </div>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const statusEl = document.getElementById('status');

            statusEl.textContent = 'Memproses...';

            fetch('/checkout/pay', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, email })
            })
            .then(async response => {
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error('Server error');
                }
                return response.json();
            })
            .then(data => {
                statusEl.textContent = '';
                snap.pay(data.snap_token, {
                    onSuccess: function () {
                        statusEl.textContent = 'Pembayaran berhasil!';
                    },
                    onPending: function () {
                        statusEl.textContent = 'Menunggu pembayaran...';
                    },
                    onError: function () {
                        statusEl.textContent = 'Terjadi kesalahan saat pembayaran.';
                    },
                    onClose: function () {
                        statusEl.textContent = 'Anda menutup popup sebelum menyelesaikan pembayaran.';
                    }
                });
            })
            .catch(() => {
                statusEl.textContent = 'Gagal menghubungi server. Pastikan server Laravel aktif.';
            });
        });
    </script>
</body>
</html>

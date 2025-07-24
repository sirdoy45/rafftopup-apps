<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin: 0; }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 60px; /* Atur ukuran logo */
            height: auto;
            margin-right: 15px;
        }

        .header-title {
            flex: 1;
            text-align: left;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }

        .signature {
            width: 100%;
            margin-top: 50px;
            text-align: right;
            line-height: 1.6;
        }

        .signature .sign-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 150px; /* Lebar garis disesuaikan */
            margin-top: 50px; /* Jarak antara "Admin" dan garis tanda tangan */
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="/images/Raffstore.png.png" alt="Logo">
        <div class="header-title">
            <h2>{{ $judul }}</h2>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Invoice</th>
                <th>Nama</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->payment_method ?? '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature">
        Dumai, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
        Admin<br>
        <span class="sign-line"></span>
    </div>

</body>
</html>

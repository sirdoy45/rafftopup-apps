<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .kop-surat table {
            width: 100%;
        }

        .kop-surat img {
            width: 70px;
        }

        .kop-surat .text-header {
            text-align: center;
            line-height: 1.4;
        }

        .kop-surat .text-header .judul {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-surat .text-header .alamat,
        .kop-surat .text-header .kontak {
            font-size: 12px;
        }

        .garis-dua {
            border: none;
            border-top: 2px solid black;
            margin: 5px 0 1px 0;
        }

        .garis-satu {
            border: none;
            border-top: 1px solid black;
            margin: 0;
        }

        h2 {
            text-align: center;
            margin: 15px 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .signature {
            width: 100%;
            margin-top: 50px;
            text-align: right;
            line-height: 1.6;
        }

        .signature .sign-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 150px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <table>
            <tr>
                <td width="80" align="center">
                    <img src="/images/Raffstore.png.png" alt="Logo">
                </td>
                <td class="text-header">
                    <div class="judul">RAFF STORE</div>
                    <div class="alamat">JL. Soekarno Hatta, Simpang Tiga JL. Pawang Sidik, Dumai</div>
                    <div class="kontak">Telepon: 081275862161 | Email: raffstore969@gmail.com</div>
                </td>
            </tr>
        </table>
        <hr class="garis-dua">
        <hr class="garis-satu">
    </div>

    <!-- Judul -->
    <h2>{{ $judul }}</h2>

    <!-- Tabel Transaksi -->
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

    <!-- Tanda Tangan -->
    <div class="signature">
        Dumai, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
        Admin<br>
        <span class="sign-line"></span>
    </div>

</body>
</html>

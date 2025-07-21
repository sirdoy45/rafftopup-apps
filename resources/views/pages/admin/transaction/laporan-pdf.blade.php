<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>{{ $judul }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
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
</body>
</html>

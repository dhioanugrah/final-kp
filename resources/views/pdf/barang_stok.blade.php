<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header-table, .approval-table { border: none; width: 100%; }
        .header-table td { padding: 5px; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Stok Barang</h2>
    <table class="header-table">
        <tr>
            <td><strong>Tanggal:</strong> {{ now()->format('d M Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Ukuran</th>
                <th>Part Number</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Minimal Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $barang)
            <tr>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->merk ?? '-' }}</td>
                <td>{{ $barang->ukuran ?? '-' }}</td>
                <td>{{ $barang->part_number ?? '-' }}</td>
                <td>{{ $barang->satuan }}</td>
                <td style="color: {{ $barang->stok <= $barang->min_stok ? 'red' : 'black' }}">
                    {{ $barang->stok }} {{ $barang->stok <= $barang->min_stok ? '⚠️' : '' }}
                </td>
                <td>{{ $barang->min_stok }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

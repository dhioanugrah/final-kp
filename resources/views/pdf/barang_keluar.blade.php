<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Keluar</title>
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
    <h2 style="text-align: center;">Laporan Barang Keluar</h2>
    <table class="header-table">
        <tr>
            <td><strong>Periode: {{ request('tableFilters.tanggal.from') }} - {{ request('tableFilters.tanggal.to') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Ukuran</th>
                <th>Part Number</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Pengguna</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutasiBarang as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->barang->nama_barang }}</td>
                <td>{{ $barang->barang->merk ?? '-' }}</td>
                <td>{{ $barang->barang->ukuran ?? '-' }}</td>
                <td>{{ $barang->barang->part_number ?? '-' }}</td>
                <td>{{ $barang->tanggal }}</td>
                <td>{{ $barang->jumlah }}</td>
                <td>{{ $barang->pengguna }}</td>
                <td>{{ $barang->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

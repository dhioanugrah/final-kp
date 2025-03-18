<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PR</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Process Request - PR #{{ $pr->id }}</h3>
    <p><strong>No PR:</strong> {{ $pr->no_pr }}</p>
    <p><strong>Tanggal Diajukan:</strong> {{ $pr->tanggal_diajukan }}</p>
    <p><strong>Required For:</strong> {{ $pr->required_for }}</p>
    <p><strong>Request By:</strong> {{ $pr->request_by }}</p>

    <h4>Pr Details</h4>
    <table>
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Ukuran</th>
                <th>Part Number</th>
                <th>Jumlah Diajukan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pr->prDetails as $detail)
            <tr>
                <td>{{ $detail->kode_barang }}</td>
                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                <td>{{ $detail->barang->merk ?? '-' }}</td>
                <td>{{ $detail->barang->ukuran ?? '-' }}</td>
                <td>{{ $detail->barang->part_number ?? '-' }}</td>
                <td>{{ $detail->jumlah_diajukan }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header-table, .approval-table { border: none; width: 100%; }
        .header-table td { padding: 5px; }
        .approval-table td { padding: 15px; text-align: center; border: 1px solid black; }
        .approval-table .left-align { text-align: left; padding-left: 5px; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Purchase Request</h2>
    <table class="header-table">
        <tr>
            <td><strong>Created Date:</strong> {{ $pr->tanggal_diajukan }}</td>
            <td><strong>No PR:</strong> {{ $pr->no_pr }}</td>
        </tr>
        <tr>
            <td><strong>Requested By:</strong> {{ $pr->request_by }}</td>
            <td><strong>Required Date:</strong> {{ $pr->required_date }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Required For</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pr->prDetails as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                <td>{{ $detail->jumlah_diajukan }}</td>
                <td>{{ $detail->barang->satuan ?? '-' }}</td>
                <td>{{ $detail->required_for ?? '-' }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <h4>Priority Level</h4>
    <p>
        <input type="checkbox"> Normal
        <input type="checkbox"> Urgent
        <input type="checkbox"> TOP Urgent
    </p>

    <h4>Approvals</h4>
    <table class="approval-table">
        <tr>
            <td><strong>Requested By</strong></td>
            <td><strong>Approved By</strong></td>
            <td><strong>Approved By</strong></td>
            <td><strong>Received By</strong></td>
        </tr>
        <tr>
            <td style="height: 50px;"></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td class="left-align">Nama: {{ $pr->requested_by_name }}</td>
            <td class="left-align">Nama: {{ $pr->approved_by_1_name }}</td>
            <td class="left-align">Nama: {{ $pr->approved_by_2_name }}</td>
            <td class="left-align">Nama: {{ $pr->received_by_name }}</td>
        </tr>
        <tr>
            <td class="left-align">Dept: {{ $pr->requested_by_dept }}</td>
            <td class="left-align">Dept: {{ $pr->approved_by_1_dept }}</td>
            <td class="left-align">Dept: {{ $pr->approved_by_2_dept }}</td>
            <td class="left-align">Dept: {{ $pr->received_by_dept }}</td>
        </tr>
    </table>
</body>
</html>

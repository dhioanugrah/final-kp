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
        .badge-approved { color: green; font-weight: bold; }
        .badge-pending { color: orange; font-weight: bold; }
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
            <td><strong>Required Date:</strong> {{ $pr->required_for }}</td>
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



    <h4>Status Approval</h4>
    <table>
        <thead>
            <tr>
                <th>Checker 1 Status</th>
                <th>Checker 2 Status</th>
                <th>Direktur Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($pr->checker_1_status == 'disetujui')
                        <span class="badge-approved">Disetujui</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>
                <td>
                    @if($pr->checker_2_status == 'disetujui')
                        <span class="badge-approved">Disetujui</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>
                <td>
                    @if($pr->direktur_status == 'disetujui')
                        <span class="badge-approved">Disetujui</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    @if($pr->checker_2_status == 'disetujui')
        <h4>Approvals</h4>
        <table class="approval-table">
            <tr>
                <td><strong>Requested By</strong></td>
                <td><strong>Checker 1</strong></td>
                <td><strong>Checker 2</strong></td>
                <td><strong>Direktur</strong></td>
            </tr>
            <tr>
                <td style="height: 50px;"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">Name: {{ $pr->request_by }}</td>
                <td class="left-align">Status: {{ $pr->checker_1_status }}</td>
                <td class="left-align">Status: {{ $pr->checker_2_status }}</td>
                <td class="left-align">Status: {{ $pr->direktur_status }}</td>
            </tr>
        </table>
    @else
        <p><em>Menunggu persetujuan Checker 2 sebelum menampilkan tanda tangan approval.</em></p>
    @endif

</body>
</html>
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
        .badge-approved { color: green; font-weight: bold; }
        .badge-pending { color: orange; font-weight: bold; }
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
            <td><strong>Required Date:</strong> {{ $pr->required_for }}</td>
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


    <h4>Status Approval</h4>
    <table>
        <thead>
            <tr>
                <th>Checker 1 Status</th>
                <th>Checker 2 Status</th>
                <th>Direktur Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($pr->checker_1_status == 'disetujui')
                        <span class="badge-approved">Disetujui</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>
                <td>
                    @if($pr->checker_2_status == 'disetujui')
                        <span class="badge-approved">Disetujui</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>
                <td>
                    @if($pr->direktur_status == 'disetujui')
                        <span class="badge-approved">Disetujui</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    @if($pr->checker_2_status == 'disetujui')
        <h4>Approvals</h4>
        <table class="approval-table">
            <tr>
                <td><strong>Requested By</strong></td>
                <td><strong>Checker 1</strong></td>
                <td><strong>Checker 2</strong></td>
                <td><strong>Direktur</strong></td>
            </tr>
            <tr>
                <td style="height: 50px;"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="left-align">Name: {{ $pr->request_by }}</td>
                <td class="left-align">Status: {{ $pr->checker_1_status }}</td>
                <td class="left-align">Status: {{ $pr->checker_2_status }}</td>
                <td class="left-align">Status: {{ $pr->direktur_status }}</td>
            </tr>
        </table>
    @else
        <p><em>Menunggu persetujuan Checker 2 sebelum menampilkan tanda tangan approval.</em></p>
    @endif

</body>
</html>

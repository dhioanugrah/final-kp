<!-- resources/views/filament/modals/detail_barang.blade.php -->
<table class="w-full border border-gray-300 rounded-lg">
    <thead class="bg-gray-800">
        <tr>
            <th class="border px-4 py-2">Jumlah Diterima</th>
            <th class="border px-4 py-2">Vendor</th>
            <th class="border px-4 py-2">Tanggal Diterima</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($penerimaan as $data)
            <tr>
                <td class="border px-4 py-2">{{ $data->jumlah_diterima }}</td>
                <td class="border px-4 py-2">{{ $data->vendor }}</td>
                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($data->tanggal_diterima)->format('d-m-Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="border px-4 py-2 text-center">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

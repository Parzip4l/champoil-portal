<table>
    <thead>
        <tr>
            <th>NIK</th>
            <th>Nama</th>
            <th>Status Anggota</th>
            <th>Status Pinjaman</th>
            <th>Saldo Simpanan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($anggota as $data)
        <tr>
            <td>{{ $data->employee_code }}</td>
            <td>{{ $data->nama }}</td>
            <td>{{ $data->member_status }}</td>
            <td>{{ $data->loan_status }}</td>
            <td>{{ number_format($data->saldo_simpanan, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

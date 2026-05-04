<table>
    <tr>
        <td><b>Tanggal Pembuatan dokumen Service</b></td>
        <td><center>:</center></td>
        <td>{{ $created_at->format('d F Y') }}</td>
    </tr>
    <tr>
        <td><b>No. Service</b></td>
        <td><center>:</center></td>
        <td><b><u>{{ $noService }}</u></b></td>
    </tr>
    <tr>
        <td><b>Nama / NIK</b></td>
        <td><center>:</center></td>
        <td>{{ $name . ' / ' . $NIK }}</td>
    </tr>
    <tr>
        <td><b>No. Ext</b></td>
        <td><center>:</center></td>
        <td>{{ $ext }}</td>
    </tr>
    <tr>
        <td><b>Perusahaan</b></td>
        <td><center>:</center></td>
        <td>{{ $company }}</td>
    </tr>
    <tr>
        <td><b>Region</b></td>
        <td><center>:</center></td>
        <td>{{ $regional }}</td>
    </tr>
    <tr>
        <td><b>Bisnis Unit</b></td>
        <td><center>:</center></td>
        <td>{{ $businessunit }}</td>
    </tr>
    <tr>
        <td><b>Departement</b></td>
        <td><center>:</center></td>
        <td>{{ $department }}</td>
    </tr>
    <tr>
        <td><b>Sub. Departement</b></td>
        <td><center>:</center></td>
        <td>{{ $subdepartment }}</td>
    </tr>
    <tr>
        <td><b>Section</b></td>
        <td><center>:</center></td>
        <td>{{ $section }}</td>
    </tr>
    <tr>
        <td><b>Jabatan</b></td>
        <td><center>:</center></td>
        <td>{{ $position }}</td>
    </tr>
    <tr>
        <td><b>Status Service</b></td>
        <td><center>:</center></td>
        <td>{{ $service_status_name ?? '-' }}</td>
    </tr>
    @if (filled($pending ?? null))
        <tr>
            <td><b>Keterangan Pending</b></td>
            <td><center>:</center></td>
            <td>{{ $pending }}</td>
        </tr>
    @endif
</table>
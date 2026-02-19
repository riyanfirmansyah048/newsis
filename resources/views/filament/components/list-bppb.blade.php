<table class="w-full border-collapse border border-gray-400">
    <thead>
        <tr>
            <th class="border border-gray-300">No. BPPB</th>
            <th class="border border-gray-300">Tanggal BPPB</th>
            <th class="border border-gray-300">Status BPPB</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bppbs as $bppb)
            <tr>
                <td class="border border-gray-300"><center>{{ $bppb->noBppb }}</center></td>
                <td class="border border-gray-300"><center>{{ \Carbon\Carbon::parse($bppb->tanggal_bppb)->translatedFormat('d F Y') }}</center></td>
                <td class="border border-gray-300"><center>{{ $bppb->status->name }}</center></td>
            </tr>
        @endforeach
    </tbody>
</table>
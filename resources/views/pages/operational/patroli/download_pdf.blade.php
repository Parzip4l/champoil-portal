<style>
    table, td, th {
        border: 1px solid;
        padding:5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
</style>
<h4 style="text-align:center">{{ $project->name }}</h4>
<table>
    @if($kalender)
        @foreach($kalender as $tgl)
            <tr>
                <td colspan="7">{{date('D',strtotime($tgl)).', '.date('d F Y',strtotime($tgl)) }}</td>
            </tr>
            @if($task)
                @php 
                    $no=1;
                @endphp
                @foreach($task as $row)
                   
                    <tr>
                        <td width="15" rowspan="{{ $row->count2 }}"> {{ $no }}</td>
                        <td colspan="6">{{ $row->judul }} ({{ $row->count }}/{{ $row->count2 }})</td>
                    </tr>
                        @if($row->list)
                            @php 
                                $no2=1;
                            @endphp
                            @foreach($row->list as $rs)
                                <tr>
                                    <td width="15" rowspan="{{$rs->detail_count+1}}">{{$no}}.{{ $no2 }}</td>
                                    <td colspan="5">{{ $rs->task }} ()</td>
                                </tr>
                                <tr>
                                    <td width="15">Jam</td>
                                    <td>Status</td>
                                    <td>Keterangan</td>
                                    <td>Foto</td>
                                    <td>Petugas</td>
                                </tr>
                                @if($rs->detail)
                                    @foreach($rs->detail as $det)
                                        <tr>
                                            <td>
                                                {{ date('d F Y H:i:s',strtotime($det->ceated_at))}}
                                            </td>
                                            <td>Status</td>
                                            <td>Keterangan</td>
                                            <td>Foto</td>
                                            <td>Petugas</td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                                @php 
                                    $no2++;
                                @endphp
                            @endforeach
                        @endif
                    @php 
                        $no++;
                    @endphp
                @endforeach
            @endif
        @endforeach
    @endif
</table>
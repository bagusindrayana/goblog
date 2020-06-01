@php
    $datas = Helper::archiveList();
    $cek = "";
@endphp
<div class="card my-4" >
    <h5 class="card-header">Archive</h5>
    <div class="card-body" style="max-height:400px;overflow-y:scroll;">
        <ul>
            @foreach ($datas as $data)
                @if ($data->year != $cek)
                    @if ($cek != "")
                        </ul>
                    @endif
                    @php
                        $cek = $data->year;
                    @endphp
                    <li>
                        <a href="{{ route('blog.archive.year',$data->year) }}">{{ $data->year }} ({{ $data->post_count }})</a>
                    </li>
                    <ul>
                        
                @else
                    <li>
                        <a href="{{ route('blog.archive.year-month',[$data->year,$data->month]) }}">{{ $data->month_name }} ({{ $data->post_count }})</a>
                    </li>
                @endif
    
                
            @endforeach
        </ul>
    </div>
</div>
    
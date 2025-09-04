<div class="card shadow">
    <div class="card-header shadow">
        <h3 class="card-title">{{ $card_title }}</h3>
        {{--                        <div class="card-tools">--}}
        {{--                            <a href="#" class="btn btn-tool btn-sm">--}}
        {{--                                <i class="fas fa-download"></i>--}}
        {{--                            </a>--}}
        {{--                            <a href="#" class="btn btn-tool btn-sm">--}}
        {{--                                <i class="fas fa-bars"></i>--}}
        {{--                            </a>--}}
        {{--                        </div>--}}
    </div>
    <div class="card-body table-responsive p-0 scrollable-div">
        <table class="table table-striped table-hover table-valign-middle">
            <thead class="shadow" style="z-index: 9">
            <tr>
                <th class="sticky-column">S/N</th>
                <th class="sticky-column">{{ $y_name }}</th>
                <th>Total</th>
                @foreach($x_axis as $x)
                    <th>@if($x == 'All In One (AIO)')<abbr title="{{ $x }}">AIO</abbr>@else {{ $x }} @endif </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($y_axis as $y)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="sticky-column shadow">{{ $y }}</td>
                    <td>{{ $matrix[$y]['total'] }}</td>
                    @foreach($x_axis as $x)
                        <td>@if(isset($matrix[$y][$x]))
                                {{ $matrix[$y][$x] }}
                            @endif</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

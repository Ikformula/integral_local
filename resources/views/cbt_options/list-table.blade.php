<div class="table-responsive">

    <table class="table table-striped ">
        <thead>
        <tr>
            <th>CBT Question</th>
            <th>Option text</th>
            <th>Correct option</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cbtOptions as $cbtOption)
            <tr>
                <td class="align-middle">{{ optional($cbtOption->CbtQuestion)->question }}</td>
                <td>{{ $cbtOption->body }}</td>
                <td class="text-end">
                    @if($cbtOption->is_correct) Correct @else - @endif

{{--                    <form method="POST" action="{!! route('cbt_options.cbt_option.destroy', $cbtOption->id) !!}" accept-charset="UTF-8">--}}
{{--                        <input name="_method" value="DELETE" type="hidden">--}}
{{--                        {{ csrf_field() }}--}}

{{--                        <div class="btn-group btn-group-sm" role="group">--}}
{{--                            <a href="{{ route('cbt_options.cbt_option.show', $cbtOption->id ) }}" class="btn btn-info" title="Show CBT Option">--}}
{{--                                <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>--}}
{{--                            </a>--}}
{{--                            <a href="{{ route('cbt_options.cbt_option.edit', $cbtOption->id ) }}" class="btn btn-primary" title="Edit CBT Option">--}}
{{--                                <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>--}}
{{--                            </a>--}}

{{--                            <button type="submit" class="btn btn-danger" title="Delete CBT Option" onclick="return confirm(&quot;Click Ok to delete CBT Option.&quot;)">--}}
{{--                                <span class="fa-regular fa-trash-can" aria-hidden="true"></span>--}}
{{--                            </button>--}}
{{--                        </div>--}}

{{--                    </form>--}}

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

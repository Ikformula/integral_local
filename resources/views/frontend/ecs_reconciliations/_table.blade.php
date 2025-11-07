
<table class="table table-bordered w-100">
    <thead>
    <tr>
        <th>#</th>
        <th>For Date</th>
        <th>ECS Sales Amount</th>
        <th>Crane Sales Amount</th>
        <th>Amounts Difference</th>
        <th>Comment</th>
        <th>Agent</th>
{{--        <th>Actions</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach($items as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->for_date->toDateString() }}</td>
            <td>{{ number_format($item->ecs_sales_amount) }}</td>
            <td>{{ number_format($item->ibe_sales_amount) }}</td>
            <td>{{ number_format($item->amounts_difference) }}</td>
            <td>{{ $item->comment }}</td>
            <td>{{ $item->agent_user_idRelation->full_name }}</td>

            {{--            <td>--}}
            {{--                                        <a href="{{ route('frontend.ecs_reconciliations.show', $item->id) }}" class="btn btn-sm btn-info">View</a>--}}
            {{--                                        <a href="{{ route('frontend.ecs_reconciliations.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>--}}
            {{--                                        <form action="{{ route('frontend.ecs_reconciliations.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">--}}
            {{--                                            @csrf--}}
            {{--                                            @method('DELETE')--}}
            {{--                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>--}}
            {{--                                        </form>--}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

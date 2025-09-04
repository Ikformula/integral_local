<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Columns</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Map Columns</h1>
    <form id="importFormMMM" action="{{ url("/import-data") }}" method="POST">
        @csrf
        <input type="hidden" name="table" value="{{ $table }}">
        <input type="hidden" name="csv_data" value="{{ json_encode($csvData) }}">
        <table class="table">
            <thead>
            <tr>
                <th>Database Column</th>
                <th>CSV Column</th>
                <th>Conversion</th>
            </tr>
            </thead>
            <tbody>
            @foreach($columns as $column)
                <tr>
                    <td>{{ $column }} ({{ $columnTypes[$column] }})</td>
                    <td>
                        <select class="form-control" name="mapping[{{ $column }}]">
                            @if(isset($columnMatches[$column]))
                            <option value="{{ $columnMatches[$column] }}" selected>* {{ $columnMatches[$column] }}</option>
                            @endif
                            <option value="">-- Select CSV Column --</option>
                            @foreach($sortedHeaders as $index => $header)
                                @if($header != '')
                                <option value="{{ $index }}">{{ $header }}
                                @endif
                            @endforeach
                        </select>
                    </td>
                    <td>
                        @if(in_array($columnTypes[$column], ['date', 'timestamp']))
                            <select class="form-control" name="conversions[{{ $column }}]">
                                <option value="">No Conversion</option>
                                <option value="date">Convert to Date</option>
                                <option value="timestamp">Convert to Timestamp</option>
                            </select>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Import Data</button>
    </form>
    <div id="progress" class="mt-3" style="display: none;">
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
        </div>
    </div>
    <div id="report" class="mt-3"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $('#progress').show();
            $('.progress-bar').css('width', '0%');

            $.ajax({
                url: '{{ url("/import-data") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('.progress-bar').css('width', '100%');
                    $('#report').html(`
                            <div class="alert alert-success">
                                Import completed:<br>
                                Inserted: ${response.inserted}<br>
                                Failed: ${response.failed}
                            </div>
                        `);
                },
                error: function() {
                    $('#report').html('<div class="alert alert-danger">An error occurred during import.</div>');
                }
            });
        });
    });
</script>
</body>
</html>

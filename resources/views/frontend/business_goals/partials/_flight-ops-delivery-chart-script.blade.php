<script>

    @php
        $delays = json_decode($presentation_data[$week][$delays_id]['total']);
       $delays_data = $delays_details = [];
       foreach ($delays as $delay){
           $delays_data[] = get_object_vars($delay);
       }

       $sum = 0;
       foreach ($delays_data as $key => $value_array){
           $delays_details[$key] = ['details' => $delay_codes->where('delay_codes', array_key_first($value_array))->first(), 'value' => $value_array[array_key_first($value_array)]];
           $sum += $delays_details[$key]['value'];
       }

       // Get data from PHP array
           $count = 1; $avg = [];
           $delay_occurences_colours = [];
    @endphp
    var delay_occurences = {
        @foreach($delays_details as $delays_detail)
            {{--            @php $avg[$count] = number_format(($delays_detail['value']/$sum) * 100, 2); @endphp--}}
            {{--"{{ $delays_detail['details']->delay_reason }}":{{ $avg[$count] }},--}}
        "{{ $delays_detail['details']->delay_codes }} - {{ $delays_detail['details']->delay_reason }}":{{ $delays_detail['value'] }},
        @php
            $count++;
            $delay_occurences_colours[$delays_detail['details']->delay_codes] = $delays_detail['details']->colour_code;
        @endphp
        @endforeach
    };


    // Convert object to array and sort by value (occurrence)
    var sortedArray = Object.entries(delay_occurences).sort((a, b) => b[1] - a[1]);

    // Convert back to object (optional, if you want the result as an object)
    var sortedDelayOccurences = Object.fromEntries(sortedArray);

    var colourObj = @json($delay_occurences_colours);
    // Create sorted colour object based on sortedArray
    var sortedColourObj = {};
    sortedArray.forEach(([key, value]) => {
        // Extract delay code from each key (assumes code is before the hyphen)
        var delayCode = key.split(" - ")[0];
        sortedColourObj[delayCode] = colourObj[delayCode];
    });

    var sortedColors = Object.keys(sortedDelayOccurences).map(key => {
        var delayCode = key.split(" - ")[0]; // Get the delay code from the key
        return sortedColourObj[delayCode];
    });


    // Extract labels and values from the delay_occurences array
    var labels = Object.keys(sortedDelayOccurences);
    var values = Object.values(sortedDelayOccurences);
    // var ctx = document.getElementById('delay_occurencesChart').getContext('2d');
{{--    @php $delay_occurences_colours = generateDistinctPastelColors($count - 1, true); @endphp--}}
    var charts = document.getElementsByClassName('{{ $chart_className }}');
    for (var i = 0; i < charts.length; i++) {
        var ctx = charts[i].getContext('2d');
        // Initialize chart for each element
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Delay Occurrences',
                    data: values,
                    backgroundColor: sortedColors,
                    borderColor: sortedColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    }

</script>

<?php

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                return 'admin.dashboard';
            }

            return 'frontend.user.dashboard';
        }

        return 'frontend.index';
    }
}

if (!function_exists('staff_read_pdf')) {
    function staff_read_pdf($staff_ara_id, $pdf_id)
    {
        return \App\Models\PdfRead::where('staff_ara_id', $staff_ara_id)->where('pdf_id', $pdf_id)->first();
    }
}

if (!function_exists('jsonToHtmlTable')) {

    function jsonToHtmlTable($jsonString)
    {
        // Convert the JSON string to an associative array
        $data = json_decode($jsonString, true);

        if (!$data) {
            return '<p>Invalid JSON input.</p>';
        }

        // Check if the JSON has the "asset" key with nested data
        if (isset($data['asset'])) {
            $data = $data['asset'];
        }

        // Start building the HTML table
        $htmlTable = '<table class="table table-striped">';

        // Header row
        $htmlTable .= '<tr>';
        foreach (array_keys($data) as $key) {
            $htmlTable .= '<th>' . htmlspecialchars($key) . '</th>';
        }
        $htmlTable .= '</tr>';

        // Data row
        $htmlTable .= '<tr>';
        foreach ($data as $value) {
            $htmlTable .= '<td>' . htmlspecialchars($value) . '</td>';
        }
        $htmlTable .= '</tr>';

        // Close the table
        $htmlTable .= '</table>';

        return $htmlTable;
    }

}

if (!function_exists('array2table')) {
    function array2table($array, $recursive = false, $null = '&nbsp;')
    {
        // Sanity check
        if (empty($array) || !is_array($array)) {
            return false;
        }
        if (!isset($array[0]) || !is_array($array[0])) {
            $array = array($array);
        }
        // Start the table
        $table = "<div class='table-responsive'> <table class='table'>\n";
        // The body
        foreach ($array as $row) {
            $table .= "\t<tr>";
            foreach ($row as $cell) {
                $table .= '<td>';
                // Cast objects
                if (is_object($cell)) {
                    $cell = (array)$cell;
                }
                if ($recursive === true && is_array($cell) && !empty($cell)) {
                    // Recursive mode
                    $table .= "\n" . array2table($cell, true, true) . "\n";
                } else {
                    $table .= strlen($cell) > 0 ? "<strong>" . htmlspecialchars((string)$cell) . "</strong>" : $null;
                }
                $table .= '</td>';
            }
            $table .= "</tr>\n";
        }
        $table .= '</table></div>';
        return $table;
    }

}

if (!function_exists('generateRows')) {
//    function generateRows($data, &$html, $isChild = false)
//    {
//        foreach ($data as $key => $value) {
//            $rowSpan = is_array($value) ? count($value) : 1;
//
//            $html .= '<tr>';
//
//            if (!$isChild) {
//                $html .= '<td rowspan="' . $rowSpan . '">' . $key . '</td>';
//            }
//
//            if (is_array($value)) {
//                generateRows($value, $html, true);
//            } else {
//                $html .= '<td rowspan="' . $rowSpan . '">' . $key . '</td> <td>' . $value . '</td>';
//            }
//
//            $html .= '</tr>';
//        }
//    }

    function generateRows($data)
    {
        $html = '';
        foreach ($data as $key => $value) {
            if (!in_array($key, ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at',]))
                if (is_array($value)) {
                    $html .= generateRows($value);
                } else {
                    $html .= '<tr><td><strong>' . $key . '</strong></td> <td>' . $value . '</td></tr>';
                }
        }
        return $html;
    }
}

if (!function_exists('generatePastelColor')) {
    function generatePastelColor()
    {
        $red = mt_rand(10, 100);
        $green = mt_rand(150, 255);
        $blue = mt_rand(150, 255);

        return "rgb($red, $green, $blue)";
    }
}

if (!function_exists('generateDistinctPastelColors')) {
    function generateDistinctPastelColors($n, $notPastel = false)
    {
        $colors = array();
        $hueIncrement = 360 / $n;

        // Start hue at a random value
        $hue = mt_rand(0, 360);

        // Generate n distinct colors
        for ($i = 0; $i < $n; $i++) {
            // Convert HSL to RGB
            $rgb = $notPastel == false ? hslToRgb($hue, 70, 85) : hslToRgb($hue, 90, 15); // Saturation: 70%, Lightness: 85%

            // Convert RGB to hexadecimal color representation
            $colorHex = sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);

            // Add color to the array
            $colors[] = $colorHex;

            // Increment hue for the next color
            $hue = ($hue + $hueIncrement) % 360;
        }

        return $colors;
    }
}

// Function to convert HSL to RGB
function hslToRgb($h, $s, $l)
{
    $h /= 360;
    $s /= 100;
    $l /= 100;

    $r;
    $g;
    $b;

    if ($s == 0) {
        $r = $g = $b = $l;
    } else {
        $hue2rgb = function ($p, $q, $t) {
            if ($t < 0) $t += 1;
            if ($t > 1) $t -= 1;
            if ($t < 1 / 6) return $p + ($q - $p) * 6 * $t;
            if ($t < 1 / 2) return $q;
            if ($t < 2 / 3) return $p + ($q - $p) * (2 / 3 - $t) * 6;
            return $p;
        };

        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;
        $r = $hue2rgb($p, $q, $h + 1 / 3);
        $g = $hue2rgb($p, $q, $h);
        $b = $hue2rgb($p, $q, $h - 1 / 3);
    }

    return array(round($r * 255), round($g * 255), round($b * 255));
}


if (!function_exists('getColumnDataType')) {
    function getColumnDataType($model, $column)
    {
        $modelInstance = app()->make("App\Models\\$model");

        $columnTypes = $modelInstance->getConnection()->getDoctrineColumnTypes();

        if (isset($columnTypes[$modelInstance->getTable()][$column])) {
            return $columnTypes[$modelInstance->getTable()][$column]->getName();
        }

        return null;
    }
}

if (!function_exists('getFeCellValue')) {
    function getFeCellValue($cellValues, $cellName)
    {
        $res = $cellValues->where('cell_name', $cellName)->pluck('cell_value');
        return $res[0] ?? null;
    }
}

if (!function_exists('findFirstArrayWithValue')) {
    function findFirstArrayWithValue($arrays, $searchString)
    {
        foreach ($arrays as $array) {
            $array = (array)$array;
            if (in_array($searchString, $array, true)) {
                return $array; // Return the first matching array
            }
        }

        return null; // No matching array found
    }
}

if (!function_exists('convertSlugToCapitalized')) {
    function convertSlugToCapitalized($slug)
    {
        // Split the slug by underscores
        $words = explode('_', $slug);

        // Capitalize each word
        $capitalizedWords = array_map('ucwords', $words);

        // Join the capitalized words with spaces
        $capitalizedString = implode(' ', $capitalizedWords);

        return $capitalizedString;
    }
}

if (!function_exists('generateNumericOTP')) {
    function generateNumericOTP($n)
    {

        // Take a generator string which consist of
        // all numeric digits
        $generator = "1357902468";

        // Iterate for n-times and pick a single character
        // from generator and append it to $result

        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        // Return result
        return $result;
    }
}

if (!function_exists('commercialCustRelationsArr')) {
    function commercialCustRelationsArr()
    {
        return array(
            array(
                'Escalation type' => 'Online reservation error',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 43,
                'PENDING' => 9
            ),
            array(
                'Escalation type' => 'New Refund requests',
                'Escalation Nature' => 'Request',
                'CLOSED' => '',
                'PENDING' => 9
            ),
            array(
                'Escalation type' => 'Pending refund request',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 2,
                'PENDING' => 42
            ),
            array(
                'Escalation type' => 'Web',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 1,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Downgrade',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 2,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Delayed Flight',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Denied Check-in',
                'Escalation Nature' => 'Compliant',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Denied boarding',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 4,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Cancelled Flight',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Baggage',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Fraudulent use of ticket',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Forgotten item (Guest)',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Forgotten item on board',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 8,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Staff Complain (Extortion)',
                'Escalation Nature' => 'Complaintt',
                'CLOSED' => 1,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Call Centre Waiting time',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Ticket Revalidation',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Inflight Complaint',
                'Escalation Nature' => 'Complaint',
                'CLOSED' => 1,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Inflight Compliment',
                'Escalation Nature' => 'Compliment',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Ticket Validity Extension',
                'Escalation Nature' => 'Request',
                'CLOSED' => 2,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'FFP (Affinity Wings)',
                'Escalation Nature' => 'Request',
                'CLOSED' => 1,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Ticket Upgrade',
                'Escalation Nature' => 'Request',
                'CLOSED' => 1,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Name Correction',
                'Escalation Nature' => 'Request',
                'CLOSED' => 1,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Name Change',
                'Escalation Nature' => 'Request',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Ticket Transfer',
                'Escalation Nature' => 'Request',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Ticket Modification',
                'Escalation Nature' => 'Request',
                'CLOSED' => 51,
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Ticket revalidation lieu of refunds',
                'Escalation Nature' => 'Request',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Offer Redemption',
                'Escalation Nature' => 'Request',
                'CLOSED' => '',
                'PENDING' => 3
            ),
            array(
                'Escalation type' => 'Unruly Passenger',
                'Escalation Nature' => 'Feedback',
                'CLOSED' => '',
                'PENDING' => ''
            ),
            array(
                'Escalation type' => 'Inquiries/ Internal and external feedback',
                'Escalation Nature' => 'Request',
                'CLOSED' => 38,
                'PENDING' => ''
            ),
//            array(
//                'Escalation type' => 'Total',
//                'Escalation Nature' => '',
//                'CLOSED' => 156,
//                'PENDING' => 63
//            )
        );
    }
}


if (!function_exists('calculateVariance')) {
    /**
     * Calculate the percentage variance between two numbers.
     * Used mainly for CEO Business Score Card Dash
     *
     * @param mixed $current The current value.
     * @param mixed $previous The previous value.
     * @return string The variance in percentage or '--' if invalid input.
     */
    function calculateVariance($current, $previous, $should_reduce = 0, $target = null)
    {
        // $should_reduce is for Stats where the desire is numbers should be reducing week on week, e.g, complains

        // Check if both inputs are numeric
        if (is_numeric($current) && is_numeric($previous)) {
            // Avoid division by zero
            if ($previous != 0) {
                // Calculate the variance and round to 2 decimal places
                $variance = (($current - $previous) / $previous) * 100;
                $colour = $should_reduce == 1 ? ($variance >= 0 ? 'danger' : 'success') : ($variance >= 0 ? 'success' : 'danger');

                if(!is_null($target) && $colour == 'success') {
                    if (($should_reduce == 1 && $current > $target) || ($should_reduce == 0 && $current < $target)) {
                        $colour = 'warning';
                    }
                }
                return ' <button class="btn float-right bg-' . $colour . '"><i class="fa fa-arrow-alt-circle-' . ($variance > 0 ? 'up' : 'down') . '"></i> ' . round($variance, 2) . '%</button>';
            } else {
                return '--'; // Avoid division by zero
            }
        }
        return '--'; // Return '--' if inputs are not numeric
    }
}

function calculateVarianceValueAndUI($current, $previous, $should_reduce = 0)
{
    // $should_reduce is for Stats where the desire is numbers should be reducing week on week, e.g, complains

    // Check if both inputs are numeric
    if (is_numeric($current) && is_numeric($previous)) {
        // Avoid division by zero
        if ($previous != 0) {
            // Calculate the variance and round to 2 decimal places
            $variance = (($current - $previous) / $previous) * 100;
            $colour = $should_reduce == 1 ? ($variance >= 0 ? 'danger' : 'success') : ($variance >= 0 ? 'success' : 'danger');
            return [' <button class="btn float-right bg-' . $colour . '"><i class="fa fa-arrow-alt-circle-' . ($variance > 0 ? 'up' : 'down') . '"></i> ' . round($variance, 2) . '%</button>', $variance];
        } else {
            return ['--', null]; // Avoid division by zero
        }
    }
    return ['--', null]; // Return '--' if inputs are not numeric
}

if(!function_exists('bscTargetReachColour')){
    function bscTargetReachColour($targetNum, $amount){
        if(!is_numeric($targetNum) || !is_numeric($amount)){
            return 'text-dark';
        }

        if($amount >= $targetNum)
            return 'text-success';

        return 'text-danger';
    }
}

if (!function_exists('icuNumFormatter')) {
    function icuNumFormatter($amount, $notation = 'Number')
    {
        if ($notation == 'Number') {
            return $amount;
        } else if (!$amount) {
            return '0';
        }
        return number_format($amount, 2);
    }
}

if(! function_exists('varianceDisplay')){
    function varianceDisplay($current_week, $previous_week){
        $variance = (is_numeric($current_week) && is_numeric($previous_week)) ? $current_week - $previous_week : 'N/A';
        $variance_direction = is_numeric($variance) && $variance != 0 ? ($variance < 0 ? 'decrease' : 'increase') : '';
        $num_variance = $variance;
        $variance = is_numeric($variance) ? numberFormatMax2Prec(($variance * 1)) : $variance;
        $perc = is_numeric($num_variance) && is_numeric($previous_week) && $previous_week != 0 ? numberFormatMax2Prec(($num_variance/$previous_week) * 100) : '';
        $variance_colour = 'black';
        if($perc != ''){
            $variance_colour = $perc < 0 ? '#BA160C' : '#000f89';
            $perc = $perc.'%';
        }

        return [
            'variance' => $variance,
            'variance_direction' => $variance_direction,
            'display text' => is_numeric($variance) ? abs($variance) . ' ' . $variance_direction : 'N/A',
            'percentage' => $perc,
            'colour' => $variance_colour,
        ];
    }
}

if(!function_exists('getSettingValue')){
    function getSettingValue($key){
        $item = DB::table('settings')->where('key', $key)->first();
        if($item)
            return $item->value;

        return null;
    }
}

if(!function_exists('arrayToHtmlTable')) {
    function arrayToHtmlTable($array)
    {
        // Check if the input is an array
        if (!is_array($array) || empty($array)) {
            return '<p>No data available</p>';
        }

        // Initialize the HTML table
        $html = '<table border="1" style="border-collapse: collapse; width: 100%;">';

        // If the array is multi-dimensional or contains nested arrays, determine headers
        $headers = [];
        foreach ($array as $row) {
            if (is_array($row)) {
                $headers = array_unique(array_merge($headers, array_keys($row)));
            }
        }

        // If headers are found, create the table headers
        if (!empty($headers)) {
            $html .= '<thead><tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr></thead>';
        }

        // Generate the table rows
        $html .= '<tbody>';
        foreach ($array as $row) {
            $html .= '<tr>';

            // If the row is an array, print its values
            if (is_array($row)) {
                foreach ($headers as $header) {
                    $value = $row[$header] ?? ''; // Use empty string for missing values
                    $html .= '<td>' . (is_array($value) ? arrayToHtmlTable($value) : htmlspecialchars($value)) . '</td>';
                }
            } else {
                // If the row is a single value, span across all columns
                $colspan = count($headers) ?: 1; // Use 1 if no headers
                $html .= '<td colspan="' . $colspan . '">' . htmlspecialchars($row) . '</td>';
            }

            $html .= '</tr>';
        }
        $html .= '</tbody>';

        // Close the HTML table
        $html .= '</table>';

        return $html;
    }
}

if(!function_exists('checkIntNumber')) {
    function checkIntNumber($num)
    {
        if (is_int($num) || $num == (int)$num) { // Check if it's an integer
            return number_format($num);
        } else {
            return number_format($num,  strlen(substr(strrchr($num, "."), 1)));
        }
    }
}

if(!function_exists('numberFormatMax2Prec')) {
    function numberFormatMax2Prec($num)
    {
        if (is_int($num) || $num == (int)$num) {
            return number_format($num);
        } else {
            return number_format($num,  2);
        }
    }
}

if(!function_exists('getQuarterNum')){
    function getQuarterNum($month_num)
    {
        if($month_num < 4){
            return 1;
        }else if($month_num >= 4 && $month_num < 7){
            return 2;
        }else if($month_num >= 7 && $month_num < 9){
            return 3;
        }

        return 4;
    }
}

if(!function_exists('checkIsJson')){
    function checkIsJson(string $string): bool {
        // Attempt to decode the string. The result isn't important for validation,
        // only whether an error occurred during the process.
        json_decode($string);

        // Check if the last JSON operation resulted in an error.
        // JSON_ERROR_NONE indicates no error, meaning the string was valid JSON.
        return json_last_error() === JSON_ERROR_NONE;
    }
}

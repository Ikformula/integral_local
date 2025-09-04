<?php

// Table columns
$tableColumns = [
    'date_f', 'charter', 'cargo_docum', 'cargo', 'corpgrpcall_centre_sales', 'travel_agents', 'paystack', 'interswitch_sales', 'web_salesteflon',
    'boh', 'mobile_sales', 'gtpayment', 'gladepay', 'excess_bag', 'penalty', 'domestic'
];

// CSV headers
$csvHeaders = [
    'date', 'nig-lhr', 'nig-acc', 'nig-coo', 'nig-jnb', 'nig-bjl', 'nig-fna', 'nig-dkr', 'nig-monrovia', 'nig-new york', 'abv-lon', 'abv-acc',
    'ngr-abidjan', 'ngr-dubai', 'nig-luanda', 'nig-duoala', 'nig-libreville', 'acc-all', 'acc cargo', 'acc agents', 'fna-all', 'dkr-all',
    'dkr agent', 'dkr cargo', 'bjl-all', 'bjl mail&prcl', 'bjl travelling', 'monrovia-all', 'mon travel agents', 'monrovia cargo', 'coo-all',
    'coo-agents', 'duoala-all', 'duoala cargo', 'abidjan-all', 'luanda', 'abidjan-cargo', 'gabon-all', 'gabon-cargo', 'charter', 'cargo docum.',
    'cargo', 'corp/grp/call centre sales', 'travel agents', 'paystack', 'interswitch sales', 'web sales-teflon', 'boh', 'mobile sales',
    'gt-payment', 'gladepay', 'excess bag', 'penalty', 'domestic', 'daily sales'
];

// Function to find the best match for each table column
function matchColumns($tableColumns, $csvHeaders) {
    $matches = [];

    foreach ($tableColumns as $column) {
        $bestMatch = null;
        $highestSimilarity = 0;

        foreach ($csvHeaders as $header) {
            similar_text($column, $header, $similarity);

            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $bestMatch = $header;
            }
        }

        $matches[$column] = $bestMatch;
    }

    return $matches;
}

// Find matches
$matchedColumns = matchColumns($tableColumns, $csvHeaders);

// Output the result
print_r($matchedColumns);
?>

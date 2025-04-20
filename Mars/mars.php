<?php

require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$apiKey = $_ENV["NASA_API_KEY"];

$params = [
    "api_key" => $apiKey,
    "feedType" => "json",
    "version" => "1.0"
];

$queryString = http_build_query($params);

$url = "https://api.nasa.gov/insight_weather/?" . $queryString;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "NASAApp/1.0");

$response = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $header_size);
curl_close($ch);

$data = json_decode($body, true);

// Initialize accumulators
$solCount = 0;
$sums = [
    'Temp_Avg' => 0,
    'Temp_Min' => 0,
    'Temp_Max' => 0,
    'Pressure_Avg' => 0,
    'Pressure_Min' => 0,
    'Pressure_Max' => 0,
    'Wind_Avg' => 0,
    'Wind_Min' => 0,
    'Wind_Max' => 0,
];
$firstUTC = '';
$lastUTC = '';
$season = '';
$northernSeason = '';
$southernSeason = '';
$windDirectionCounts = [];

foreach ($data as $sol => $details) {
    if (!is_array($details)) continue;

    $solCount++;

    $sums['Temp_Avg'] += $details['AT']['av'] ?? 0;
    $sums['Temp_Min'] += $details['AT']['mn'] ?? 0;
    $sums['Temp_Max'] += $details['AT']['mx'] ?? 0;

    $sums['Pressure_Avg'] += $details['PRE']['av'] ?? 0;
    $sums['Pressure_Min'] += $details['PRE']['mn'] ?? 0;
    $sums['Pressure_Max'] += $details['PRE']['mx'] ?? 0;

    $sums['Wind_Avg'] += $details['HWS']['av'] ?? 0;
    $sums['Wind_Min'] += $details['HWS']['mn'] ?? 0;
    $sums['Wind_Max'] += $details['HWS']['mx'] ?? 0;

    $direction = $details['WD']['most_common']['compass_point'] ?? '';
    if ($direction) {
        $windDirectionCounts[$direction] = ($windDirectionCounts[$direction] ?? 0) + 1;
    }

    // Capture the first and last date range (roughly)
    if ($solCount == 1) {
        $firstUTC = $details['First_UTC'] ?? '';
        $season = $details['Season'] ?? '';
        $northernSeason = $details['Northern_season'] ?? '';
        $southernSeason = $details['Southern_season'] ?? '';
    }
    $lastUTC = $details['Last_UTC'] ?? $lastUTC;
}

// Calculate averages
$averages = array_map(function ($sum) use ($solCount) {
    return $solCount > 0 ? round($sum / $solCount, 3) : 0;
}, $sums);

// Find most common wind direction
arsort($windDirectionCounts);
$mostCommonWindDirection = key($windDirectionCounts);

// Write to CSV
$csvFile = fopen("Comparison_csv/mars_weather.csv", "w");

fputcsv($csvFile, [
    'Sol_Count',
    'First_UTC',
    'Last_UTC',
    'Season',
    'Northern_Season',
    'Southern_Season',
    'Temp_Avg',
    'Temp_Min',
    'Temp_Max',
    'Pressure_Avg',
    'Pressure_Min',
    'Pressure_Max',
    'Wind_Avg',
    'Wind_Min',
    'Wind_Max',
    'Most_Common_Wind_Direction'
]);

fputcsv($csvFile, [
    $solCount,
    $firstUTC,
    $lastUTC,
    $season,
    $northernSeason,
    $southernSeason,
    $averages['Temp_Avg'],
    $averages['Temp_Min'],
    $averages['Temp_Max'],
    $averages['Pressure_Avg'],
    $averages['Pressure_Min'],
    $averages['Pressure_Max'],
    $averages['Wind_Avg'],
    $averages['Wind_Min'],
    $averages['Wind_Max'],
    $mostCommonWindDirection
]);

fclose($csvFile);
echo "Mars weather summary saved to mars_weather.csv\n";

?>

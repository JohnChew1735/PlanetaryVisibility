<?php
require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. "/..");
$dotenv->load();

$apiKey = $_ENV["OPEN_WEATHER_API_KEY"];

$params = [
    "q" => "Selangor,MY",  
    "appid" => $apiKey,
    "units" => "metric"   
];

$queryString = http_build_query($params);
$url = "https://api.openweathermap.org/data/2.5/weather?" . $queryString;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "WeatherApp/1.0");

$response = curl_exec($ch);

if ($response === false) {
    echo "Curl error: " . curl_error($ch);
    exit;
}

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $header_size);
curl_close($ch);

$data = json_decode($body, true);

if (!isset($data['main'])) {
    echo "Error: Unexpected API response.";
    exit;
}

// Prepare CSV
$csvFile = fopen("Comparison_csv/earth_weather.csv", "w");

fputcsv($csvFile, [
    'Location',
    'DateTime_UTC',
    'Weather_Description',
    'Temperature_C',
    'Feels_Like_C',
    'Temp_Min_C',
    'Temp_Max_C',
    'Pressure_hPa',
    'Humidity_%',
    'Wind_Speed_mps',
    'Wind_Direction_deg',
    'Cloudiness_%',
]);

fputcsv($csvFile, [
    $data['name'] ?? 'Selangor',
    gmdate("Y-m-d\TH:i:s\Z", $data['dt']),
    $data['weather'][0]['description'] ?? '',
    $data['main']['temp'] ?? '',
    $data['main']['feels_like'] ?? '',
    $data['main']['temp_min'] ?? '',
    $data['main']['temp_max'] ?? '',
    $data['main']['pressure'] ?? '',
    $data['main']['humidity'] ?? '',
    $data['wind']['speed'] ?? '',
    $data['wind']['deg'] ?? '',
    $data['clouds']['all'] ?? ''
]);

fclose($csvFile);
echo "Earth weather data saved to earth_weather.csv\n";
?>

<?php
require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. "/..");
$dotenv->load();

$apiKey = $_ENV["NASA_API_KEY"];

//can change parameters to date, 
$params = [
    "api_key"=> $apiKey,
    "thumbs"=> true,
    "start_date"=> "2022-10-09",
    "end_date"=> "2022-11-01"
];

$queryString = http_build_query($params);

$url = "https://api.nasa.gov/planetary/apod?" . $queryString;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "NASAApp/1.0");

$response = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

curl_close($ch);

$data = json_decode($body, true);

for ($i = 0; $i < count($data); $i++){
    $imageUrl = $data[$i]["url"];
    $imageContent = file_get_contents($imageUrl);
    $imageName = "APOD$i.jpg";
    $descriptionName = "APOD$i.txt";
    $description = $data[$i]["explanation"];
    $folder = "APOD_images/";

// Create the folder if it doesn't exist
if (!file_exists($folder)) {
    mkdir($folder, 0777, true); // true allows creation of nested folders if needed
}

// Now you can safely write the files
file_put_contents($folder . $imageName, $imageContent);
file_put_contents($folder . $descriptionName, $description);
}

echo("\nPictures and descriptions saved in folder image\n");
?>
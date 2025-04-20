<?php
require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. "/..");
$dotenv->load();

$apiKey = $_ENV["NASA_API_KEY"];

//can change parameters to date, 
$params = [
    "api_key"=> $apiKey,
    "thumbs"=> true,
    "date"=> "2022-10-09",
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

$imageUrl = $data["url"];
$imageContent = file_get_contents($imageUrl);
$imageName = "APOD.jpg";
$descriptionName = "APOD.txt";
$description = $data["explanation"];
file_put_contents("APOD_images/" . $imageName, $imageContent);
file_put_contents("APOD_images/" . $descriptionName, $description);

echo("\nPicture and description saved in folder image\n");
?>
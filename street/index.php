<?php
include("../functions.php");
header('Content-Type: text/html; charset=utf-8');


$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];


# Get current location!
//$latitude = "37.740013";
//$longitude = "29.09372899999994";

# Use google reverse geocoding api and get street name.
$street = getStreet($latitude, $longitude);

echo $street;
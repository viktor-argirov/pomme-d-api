<?php

header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: POST");

header("Access-Control-Allow-Headers: Content-Type");

header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "api_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->name)) {
    echo json_encode(array("error" => "Missing required fields."));
    exit;
}

$sql = "SELECT * FROM pokemons WHERE Name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $data->name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $pokemon = $result->fetch_assoc();
    echo json_encode(array("message" => "Pokemon already exists in the database.", "pokemon" => $pokemon));
} else {
    $ch = curl_init("https://pokeapi.co/api/v2/pokemon/{$data->name}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(array("error" => "Error fetching data from the Pokémon API: $error"));
        exit;
    }

    $apiData = json_decode($response);

    if (!$apiData) {
        echo json_encode(array("error" => "Failed to decode JSON data from the Pokémon API."));
        exit;
    }

    $pokemonName = $apiData->name;
    $pokemonType = implode(", ", array_map(function($type) { return $type->type->name; }, $apiData->types));
    $pokemonHeight = $apiData->height;
    $pokemonWeight = $apiData->weight;
    $pokemonAbilities = implode(", ", array_map(function($ability) { return $ability->ability->name; }, $apiData->abilities));
    $pokemonImage = $apiData->sprites->front_default;

    $sql = "INSERT INTO pokemons (Name, Type, Height, Weight, Ability, Image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddss", $pokemonName, $pokemonType, $pokemonHeight, $pokemonWeight, $pokemonAbilities, $pokemonImage);

    if ($stmt->execute() === TRUE) {
        $insertedPokemon = array(
            "Name" => $pokemonName,
            "Type" => $pokemonType,
            "Height" => $pokemonHeight,
            "Weight" => $pokemonWeight,
            "Ability" => $pokemonAbilities,
            "Image" => $pokemonImage
        );
        echo json_encode(array("message" => "Pokemon added to the database.", "pokemon" => $insertedPokemon));
    } else {
        echo json_encode(array("error" => "Failed to insert Pokemon into the database."));
    }
}

$stmt->close();
$conn->close();

?>

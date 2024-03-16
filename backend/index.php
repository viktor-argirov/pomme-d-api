<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$ulElement = '<ul id="pokemonList"></ul>';

$response = file_get_contents("get_pokemons.php");
$data = json_decode($response);

if (empty($data)) {
    $apiResponse = file_get_contents("https://pokeapi.co/api/v2/pokemon/11"); 
    $apiData = json_decode($apiResponse);

    $insertResponse = file_get_contents("insert_pokemon.php", false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($apiData)
        ]
    ]));
    $insertedData = json_decode($insertResponse);

    $pokemon = $insertedData;
    $pokemonInfo = [
        "Name" => $pokemon->Name,
        "Type" => $pokemon->Type,
        "Height" => $pokemon->Height,
        "Weight" => $pokemon->Weight,
        "Ability" => $pokemon->Ability,
        "Image" => $pokemon->Image
    ];

    $liElement = '<li>';
    foreach ($pokemonInfo as $key => $value) {
        $liElement .= "<strong>$key:</strong> $value<br>";
    }
    $liElement .= '</li>';

    $ulElement .= $liElement;
} else {
    foreach ($data as $pokemon) {
        $pokemonInfo = [
            "Name" => $pokemon->Name,
            "Type" => $pokemon->Type,
            "Height" => $pokemon->Height,
            "Weight" => $pokemon->Weight,
            "Ability" => $pokemon->Ability,
            "Image" => $pokemon->Image
        ];

        $liElement = '<li>';
        foreach ($pokemonInfo as $key => $value) {
            $liElement .= "<strong>$key:</strong> $value<br>";
        }
        $liElement .= '</li>';

        $ulElement .= $liElement;
    }
}

echo $ulElement;
?>

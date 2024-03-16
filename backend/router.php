<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$requestUri = $_SERVER['REQUEST_URI'];

switch ($requestUri) {
    case '/':
        require_once 'index.php';
        break;
    case '/insert_pokemon':
        require_once 'insert_pokemon.php';
        break;
    case '/get_pokemons':
        require_once 'get_pokemons.php';
        break;
    default:
        http_response_code(404);
        echo 'Page not found';
}

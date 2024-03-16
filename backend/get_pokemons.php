<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "api_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM pokemons";
$result = $conn->query($sql);

$pokemons = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pokemons[] = $row;
    }
} else {
    $pokemons = [];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($pokemons);
?>

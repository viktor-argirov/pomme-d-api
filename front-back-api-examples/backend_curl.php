<?php 
$barcode = '3664346300780';
$url = "https://world.openfoodfacts.net/api/v2/product/{$barcode}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL certificate verification
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

echo "cURL Response: <pre>" . htmlentities($response) . "</pre>";

if ($error) {
    echo "Error during cURL request: $error";
} else {
    $data = json_decode($response);
    var_dump($data);
    if ($data && isset($data->product)) {
?>

<h1>Affichage du resultat</h1>
<p>Nom de produit: <?php echo $data->product->product_name_fr; ?></p>
<p>Marque: <?php echo $data->product->brands; ?></p>
<p>Categorie: <?php echo $data->product->categories; ?></p>
<p>Allergens: <?php echo $data->product->allergens_imported; ?></p>
<p>Ingredients: <?php echo $data->product->ingredients_text; ?></p>
<p>Image:<img src="<?php echo $data->product->image_url; ?>" alt="image de produit" width="200"></p>
<p>Pays importés: <?php echo $data->product->countries_imported; ?></p>
<pre><?php print_r($data); ?></pre>
<?php
    } else {
        echo "Error: Unable to decode JSON response or product data not found.";
    }
}
?>

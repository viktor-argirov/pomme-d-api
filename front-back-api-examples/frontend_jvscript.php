<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Information</title>
</head>
<body>
    <h1>Product Information</h1>
    <ul id="productList"></ul>

    <script>
        const ulElement = document.getElementById("productList");

        fetch("https://world.openfoodfacts.net/api/v2/product/3017624010701")
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            console.log(data);

            const product = data.product;

            const productInfo = {
                "Name": product.product_name_fr,
                "Brand": product.brands,
                "Category": product.categories,
                "Allergens": product.allergens_imported,
                "Ingredients": product.ingredients_text,
                "Image": product.image_url,
                "Imported from": product.countries_imported 
            };

            for (const [key, value] of Object.entries(productInfo)) {
                const liElement = document.createElement("li");
                liElement.innerHTML = `<strong>${key}:</strong> ${value}`;
                ulElement.appendChild(liElement);
            }
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
            const errorElement = document.createElement("li");
            errorElement.innerText = "Error fetching product information.";
            ulElement.appendChild(errorElement);
        });
    </script>
</body>
</html>

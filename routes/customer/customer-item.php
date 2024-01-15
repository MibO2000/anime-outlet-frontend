<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
</head>

<body>

    <!-- Can search with Film, Brand, Category, Name, Scale -->
    <div>
        <h1>Items</h1>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Film</th>
                    <th>Brand</th>
                    <th>Image 1</th>
                    <th>Release date</th>
                    <th>Item Description</th>
                    <th>Scale</th>
                    <th>Stock Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "select ai.item_id, ai.item_name , ac.category_name as category, af.title as film, ab.brand_name as brand, ai.item_image_1, ai.item_image_2, ai.item_image_3, ai.release_date, ai.item_description, ai.`scale`, ai.stock_quantity, ai.price from `ASSIGNMENT`.ao_item ai join `ASSIGNMENT`.ao_brand ab on ab.brand_id = ai.brand_id join `ASSIGNMENT`.ao_film af on af.film_id = ai.film_id join `ASSIGNMENT`.ao_category ac on ac.category_id = ai.category_id ";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['item_id'] . "</td>";
                    echo "<td>" . $row['item_name'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['film'] . "</td>";
                    echo "<td>" . $row['brand'] . "</td>";
                    echo "<td>" . $row['item_image_1'] . "</td>";
                    echo "<td>" . $row['release_date'] . "</td>";
                    echo "<td>" . $row['item_description'] . "</td>";
                    echo "<td>" . $row['`scale`'] . "</td>";
                    echo "<td>" . $row['stock_quantity'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
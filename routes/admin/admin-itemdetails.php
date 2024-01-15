<!-- this is for crud of film, brand and category -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item-Details</title>
</head>

<body>
    <div>
        <h1>Film</h1>
        <table>
            <thead>
                <tr>
                    <th>Film ID</th>
                    <th>Title</th>
                    <th>Film Image</th>
                    <th>Release Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "SELECT * from ASSIGNMENT.ao_film";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['film_id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['film_image'] . "</td>";
                    echo "<td>" . $row['release_date'] . "</td>";
                    echo "<td>" . $row['film_description'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div>
        <h1>Category</h1>
        <table>
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Nam</th>
                    <th>Category Image</th>
                    <th>Category Description</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "SELECT * from ASSIGNMENT.ao_category";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['category_id'] . "</td>";
                    echo "<td>" . $row['category_name'] . "</td>";
                    echo "<td>" . $row['category_image'] . "</td>";
                    echo "<td>" . $row['category_description'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div>
        <h1>Brand</h1>
        <table>
            <thead>
                <tr>
                    <th>Brand ID</th>
                    <th>Brand Nam</th>
                    <th>Brand Image</th>
                    <th>Brand Description</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "SELECT * from ASSIGNMENT.ao_brand";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['brand_id'] . "</td>";
                    echo "<td>" . $row['brand_name'] . "</td>";
                    echo "<td>" . $row['brand_image'] . "</td>";
                    echo "<td>" . $row['brand_description'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>
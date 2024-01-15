<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <h1>Supplier </h1>
        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier name</th>
                    <th>Supplier user</th>
                    <th>Supplier Password</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "select supplier_id, supplier_name, supplier_user, supplier_password, email, phone  from `ASSIGNMENT`.ao_supplier as2";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['supplier_id'] . "</td>";
                    echo "<td>" . $row['supplier_name'] . "</td>";
                    echo "<td>" . $row['supplier_user'] . "</td>";
                    echo "<td>" . $row['supplier_password'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
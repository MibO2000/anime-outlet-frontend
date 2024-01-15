<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order</title>
</head>

<body>
    <!-- can change the status of the purchase. If the status is approved, the item data needs to be changed -->
    <div>
        <h1>Purchase </h1>
        <table>
            <thead>
                <tr>
                    <th>Purchase ID</th>
                    <th>Supplier name</th>
                    <th>Admin name</th>
                    <th>Purchase Date</th>
                    <th>Purchase Status</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "select ap.purchase_id, as2.supplier_name, aa.fullname, ap.purchase_date, ap.purchase_status, ap.total_amount from `ASSIGNMENT`.ao_purchase ap join `ASSIGNMENT`.ao_supplier as2 on as2.supplier_id = ap.supplier_id left join `ASSIGNMENT`.ao_admin aa on aa.admin_id = ap.admin_id";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['purchase_id'] . "</td>";
                    echo "<td>" . $row['supplier_name'] . "</td>";
                    echo "<td>" . $row['fullname'] . "</td>";
                    echo "<td>" . $row['purchase_date'] . "</td>";
                    echo "<td>" . $row['purchase_status'] . "</td>";
                    echo "<td>" . $row['total_amount'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
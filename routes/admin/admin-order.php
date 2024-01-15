<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order</title>
</head>

<body>
    <!-- can only view teh order list and when clicked, payment and delivery will display in two tables one page (right and left) -->
    <div>
        <h1>Order</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "select ao.order_id, ac.full_name, ao.order_date, ao.order_status from `ASSIGNMENT`.ao_order ao join `ASSIGNMENT`.ao_customer ac on ao.customer_id = ac.customer_id order by ao.order_date desc";
                $result = mysqli_query($connect, $query);

                // Loop through each row and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['customer_name'] . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>" . $row['order_status'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
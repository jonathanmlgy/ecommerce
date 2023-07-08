<!DOCTYPE html>
<html>
    <head>
        <title>User</title>
    </head>
    <body>
        <h1>Manage Products</h1>
        <a href="/users/logoff">Logoff</a>
        <a href="/users/edit">Profile</a>
        <table>
            <tr>
                <th>Item ID</th>
                <th>Name</th>
                <th>Inventory Count</th>
                <th>Quantity Sold</th>
            </tr>
<?php   
        if(isset($products)) {
            foreach($products as $product => $key) {
?>
            <tr>
                <td><?=$key['product_id']?></td>
                <td><a href='/products/show/<?=$key['product_id']?>'><?=$key['product_name']?></a></td>
                <td><?=$key['inventory_count']?></td>
                <td><?=$key['quantity_sold']?></td>
            </tr>
<?php   
            }
        }
?>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
    </head>
    <body>
        <h1>Manage Products</h1>
        <a href="/users/logoff">Logoff</a>
        <a href="/products">Add new</a>
        <table>
            <tr>
                <th>Item ID</th>
                <th>Name</th>
                <th>Inventory Count</th>
                <th>Quantity Sold</th>
                <th>Action</th>
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
                <td>
                    <a href="/products/edit/<?=$key['product_id']?>">Edit</a>
                    <a href="/products/remove/<?=$key['product_id']?>">Remove</a>
                </td>
            </tr>
<?php   
            }
        }
?>
    </body>
</html>
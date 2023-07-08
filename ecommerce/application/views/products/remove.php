

<h1>Are you sure you want to remove <?=$product['product_name']?></h1>

<?php echo form_open('/products/process_remove_product/' . $product['product_id']); ?>
    <input type="submit" value="Delete">
<?php echo form_close(); ?>

</body>
</html>
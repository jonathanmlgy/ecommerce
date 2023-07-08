        <div class="error"><?=$this->session->flashdata('input_errors');?></div>

        <h1>Edit product# <?=$product_id?></h1>

        <?php echo form_open('/products/process_edit_product/'. $product_id); ?>
            <label>Name:</label>
            <input type="text" name="product_name"><br>
            <label>Description:</label>
            <input type="text" name="description"><br>
            <label>Price:</label>
            <input type="number" name="price"><br>
            <label>Inventory count:</label>
            <input type="number" name="inventory_count"><br>
            <input type="submit" value="Edit">
        <?php echo form_close(); ?>

    </body>
</html>
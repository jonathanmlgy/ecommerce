        <div class="error"><?=$this->session->flashdata('input_errors');?></div>

        <h1>Add a new product</h1>

        <?php echo form_open('/products/process_new_product'); ?>
            <label>Name:</label>
            <input type="text" name="product_name"><br>
            <label>Description:</label>
            <input type="text" name="description"><br>
            <label>Price:</label>
            <input type="number" name="price"><br>
            <label>Inventory count:</label>
            <input type="number" name="inventory_count"><br>
            <input type="submit" value="Create">
        <?php echo form_close(); ?>

    </body>
</html>
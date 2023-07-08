
        <a href='/dashboard'>Dashboard</a>
        <h1><?=$product['product_name']?> (Php <?=$product['price']?>)</h1>
        <p>Product ID: <?=$product['product_id']?></p>
        <p>Description: <?=$product['description']?></p>
        <p>Total Sold: <?=$product['quantity_sold']?></p>
        <p>Available stocks: <?=$product['inventory_count']?></p>
        <?php echo form_open('/products/process_add_review/' . $product['product_id']); ?>
            <input type="textarea" name = review_input>
            <input type="submit" value="Create review">
        <?php echo form_close(); ?>

        <?php foreach($comments as $review) {
        ?>
                <div class='review' style='width:360px; border:1px solid black;'>
                    <h2><?=$review['name']?> wrote:</h2>
                    <div style='width:300px; border:1px dotted black;'>
                        <p><?=$review['comment']?>
                    </div>
        <?php   foreach($review['replies'] as $reply) {
        ?>
                    <h3><?=$reply["name"]?></h3>
                    <p><?=$reply["reply"]?></p>
        <?php
                }
        ?>
        <?php echo form_open('/products/process_add_reply/' . $product['product_id'] . '/' . $review['comment_id'] . '/' . $review['user_id']); ?>
                    <input type="textarea" name = reply_input>
                    <input type="submit" value="Reply">
        <?php echo form_close(); ?>            
                </div>
        <?php
            }
        ?>

    </body>
</html>
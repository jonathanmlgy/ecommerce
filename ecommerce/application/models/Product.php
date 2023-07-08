<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Model {
    
    /*  DOCU: This function returns all user messages.
        Owner: Karen
    */
    public function get_products() 
    {
        $query = 'SELECT *FROM products';
        return $this->db->query($query)->result_array();
    }

    public function get_product_by_id($product_id) {
        return $this->db->query('SELECT product_id, product_name, inventory_count, quantity_sold, price, description FROM products WHERE product_id = ?', array($product_id))->row_array();
    }

    public function remove_product_by_id($product_id) 
    {
        return $this->db->query("DELETE FROM products WHERE product_id = ?", array($product_id));
    }
    /*  DOCU: This function validates the required message input.
        Owner: Karen
    */
    public function validate_product() 
    {
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');        
        $this->form_validation->set_rules('price', 'Price', 'required');
        $this->form_validation->set_rules('inventory_count', 'Inventory Count', 'required');

        if(!$this->form_validation->run()) {
            return validation_errors();
        }
        else {
            return 'success';
        }
    }

    /*  DOCU: This function inserts new message from a user to the database.
        Owner: Karen
    */
    public function add_product($post) 
    {
        $post['quantity_sold'] = 0;
        $query = 'INSERT INTO products(product_name, inventory_count, quantity_sold, price, description, users_user_id) VALUES (?,?,?,?,?,?)';
        $values = array(
            $this->security->xss_clean($post['product_name']), 
            $this->security->xss_clean($post['inventory_count']),
            $this->security->xss_clean($post['quantity_sold']),
            $this->security->xss_clean($post['price']),
            $this->security->xss_clean($post['description']),
            $this->security->xss_clean($this->session->userdata('user_id'))
        ); 
        
        $this->db->query($query, $values);
    }

    public function edit_product($post) 
    {
        $query = 'UPDATE products SET product_name = ?, inventory_count = ?, price = ?, description = ?, users_user_id = ? WHERE product_id = ?';
        $values = array(
            $this->security->xss_clean($post['product_name']), 
            $this->security->xss_clean($post['inventory_count']),
            $this->security->xss_clean($post['price']),
            $this->security->xss_clean($post['description']),
            $this->security->xss_clean($this->session->userdata('user_id')),
            $this->security->xss_clean($post['product_id'])
        );
        
        $this->db->query($query, $values);
    }

    public function validate_review()
    {
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('review_input', 'Review', 'required');

        if(!$this->form_validation->run()) {
            return validation_errors();
        }
        else {
            return 'success';
        }
    }

    public function validate_reply()
    {
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('reply_input', 'Reply', 'required');

        if(!$this->form_validation->run()) {
            return validation_errors();
        }
        else {
            return 'success';
        }
    }

    public function add_review($post, $product_id) 
    {
        $query = 'INSERT INTO comments(comment, created_at, updated_at, products_product_id, users_user_id) VALUES (?, ?, ?, ?, ?)';
        $values = array(
            $this->security->xss_clean($post['review_input']),
            date("Y-m-d, H:i:s"),
            date("Y-m-d, H:i:s"),
            $this->security->xss_clean($product_id),
            $this->security->xss_clean($this->session->userdata('user_id'))
        );
        $this->db->query($query, $values);
    }

    public function get_reviews($product_id) 
    {
        return $this->db->query(
            'SELECT comment_id, comment, products_product_id, users.user_id, CONCAT(users.first_name," ",users.last_name) AS name
            FROM comments
            LEFT JOIN users ON comments.users_user_id = users.user_id
            WHERE products_product_id = ?
            ORDER BY comments.created_at DESC', 
            array($product_id))->result_array();
    }

    public function add_reply($data) 
    {
        $query = 'INSERT INTO replies(reply, created_at, updated_at, comments_comment_id, users_user_id ) VALUES (?, ?, ?, ?, ?)';
        $values = array(
            $this->security->xss_clean($data['reply']),
            date("Y-m-d, H:i:s"),
            date("Y-m-d, H:i:s"),
            $this->security->xss_clean($data['comment_id']),
            $this->security->xss_clean($this->session->userdata('user_id')),
        );
        $this->db->query($query, $values);
    }

    public function get_replies_from_review_id($comment_id)
    {
        $safe_comment_id = $this->security->xss_clean($comment_id);

        $query = "SELECT reply_id, reply, 
        CONCAT(users.first_name,' ',users.last_name) AS name
        FROM replies
        LEFT JOIN users ON replies.users_user_id = users.user_id
        WHERE comments_comment_id = ?";
        
        return $this->db->query($query, $safe_comment_id)->result_array();

    }
}

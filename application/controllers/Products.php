<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function index() 
    {
        $this->load->view('templates/header');
        $this->load->view('products/new');
    }

    public function show($product_id)
    {
        $this->load->Model('Product');
        $product = $this->Product->get_product_by_id($product_id);
        $reviews = $this->Product->get_reviews($product_id);
        $comments = array();
        foreach($reviews as $review) {
            $this->load->Model('Product');
            $replies = $this->Product->get_replies_from_review_id($review['comment_id']);
            $review['replies'] = $replies;
            
            $comments[] = $review;
        }
        $view_data['product'] = $product;
        $view_data['comments'] = $comments;
        $this->load->view('products/show', $view_data);
    }

    public function process_new_product()
    {
        $this->load->Model('Product');
        $result = $this->Product->validate_product();
        if($result != 'success') 
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect("signin");
        } 
        else 
        {
            $post = $this->input->post();
            $this->Product->add_product($post);
            redirect('dashboard/admin');
        }
    }

    public function edit($product_id)
    {
        $view_data['product_id'] = $product_id;
        $this->load->view('templates/header');
        $this->load->view('products/edit', $view_data);
        
    }

    public function process_edit_product($product_id)
    {
        $this->load->Model('Product');
        $result = $this->Product->validate_product();
        
        if($result != 'success') 
        {
            $view_data['product_id'] = $product_id;
            $this->session->set_flashdata('input_errors', $result);
            $this->load->view('products/edit', $view_data);
        }
        else
        {
            $post = $this->input->post();
            $post['product_id'] = $product_id;
            $this->Product->edit_product($post);
            redirect('dashboard/admin');
        }
    }

    public function remove($product_id)
    {
        $this->load->Model('Product');
        $result = $this->Product->get_product_by_id($product_id);
        $view_data['product'] = $result;
        $this->load->view('products/remove', $view_data);
    }

    public function process_remove_product($product_id)
    {
        $this->load->Model('Product');
        $this->Product->remove_product_by_id($product_id);
        redirect('dashboard/admin');
    }
    ////////////////Review section////////////////
    public function process_add_review($product_id)
    {
        $this->load->Model('Product');
        $result = $this->Product->validate_review();
        if($result != 'success') 
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect('products/show/'. $product_id);
        }
        else
        {
            $this->load->Model('Product');
            $post = $this->input->post();
            $this->Product->add_review($post, $product_id);
            redirect('products/show/'. $product_id);
        }
    }

    public function process_add_reply($product_id, $comment_id, $user_id)
    {
        $this->load->Model('Product');
        $result = $this->Product->validate_reply();
        if($result != 'success') 
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect('products/show/'. $product_id);
        }
        else
        {
            $data = array(
                'reply' => $this->input->post('reply_input'),
                'comment_id' => $comment_id,
                'user_id' => $user_id
            );
            $this->load->Model('Product');
            $result = $this->Product->add_reply($data);
            redirect('products/show/'. $product_id);
        }
    }
}
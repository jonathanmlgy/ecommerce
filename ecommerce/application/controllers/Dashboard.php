<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function index()  
    {
        $this->load->Model('Product');
        $products = $this->Product->get_products();
        $param = array('first_name' => $this->session->userdata('first_name'), 'products' => $products);
        $this->load->view('dashboard/user', $param);
    }

    /*  DOCU: This function is triggered by default to render the Wall page.
        This loads all messages with comments from all users.
        Owner: Karen
    
    public function index()  
    {
        $user_messages = $this->message->get_messages();
        $inbox = array();
        foreach($user_messages as $user_message) 
        {
            $this->load->model('Comment');
            $comments = $this->Comment->get_comments_from_message_id($user_message['message_id']);
            $user_message["comments"] = $comments;
            $inbox[] = $user_message;
        }
        $param = array("first_name"=>$this->session->userdata('first_name'), "inbox"=>$inbox);
  
        $this->load->view('wall/show',$param);
    }
    */
    /*  DOCU: This function is responsible to validate and add the message from any user to the database.
        Owner: Karen
    */
    public function add_message() 
    {
        $result = $this->message->validate_message();
      
        if($result != 'success') {
            $this->session->set_flashdata('input_errors', $result);
        } 
        
        else {
            $post = $this->input->post();
            $this->message->add_message($post);
        }
        redirect("wall");
    }

    /*  DOCU: This function is responsible to validate and add the comment from any user to the database.
        Owner: Karen
    */
    public function add_comment() 
    {
        $this->load->model('Comment');
        $result = $this->Comment->validate_comment();
        //var_dump($result);
        //var_dump($this->input->post());
        if($result != 'success') {
            $this->session->set_flashdata('input_errors', validation_errors());
        }
        else {
            $post = $this->input->post();
            $this->Comment->add_comment($post);
        }
        redirect("wall");
    }

    public function admin()  
    {
        if($this->session->userdata('user_level') == 1) {
            redirect('/dashboard');
        }
        $this->load->Model('Product');
        $products = $this->Product->get_products();
        $param = array('first_name' => $this->session->userdata('first_name'), 'products' => $products);
        $this->load->view('dashboard/admin', $param);
    }
}
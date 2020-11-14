<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wall extends CI_Controller {

    public function index()  
    {
        $current_user_firstname = $this->session->userdata('first_name');

        $user_messages = $this->Message->get_messages();
        
        $inbox = array();
        foreach($user_messages as $user_message) 
        {
            $comments = $this->Comment->get_comments_from_message_id($user_message['message_id']);
            $user_message["comments"] = $comments;
            $inbox[] = $user_message;
        }
        $param = array("first_name"=>$current_user_firstname, "inbox"=>$inbox);
  
        $this->load->view('wall/show',$param);
    }


    public function add_message() 
    {
        $result = $this->Message->validate_message();
        
        if($result != 'success') {
            $this->session->set_flashdata('input_errors', $result);
        } 
        else {
            $this->Message->add_message($this->input->post());
        }
        redirect("wall");
    }


    public function add_comment() 
    {
        $result = $this->Comment->validate_comment();

        if($result != 'success') {
            $this->session->set_flashdata('input_errors', validation_errors());
        }
        else {
            $this->Comment->add_comment($this->input->post());
        }
        redirect("wall");
    }
}
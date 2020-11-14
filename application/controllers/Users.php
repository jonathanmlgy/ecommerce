<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    
    public function index() 
    {
        $current_user_id = $this->session->userdata('user_id');
        
        if(!$current_user_id) { 
            $this->load->view('templates/header');
            $this->load->view('users/signin');
        } 
        else {
            redirect("wall");
        }

    }
    
    public function signin() 
    {
        $current_user_id = $this->session->userdata('user_id');
        
        if(!$current_user_id) { 
            $this->load->view('templates/header');
            $this->load->view('users/signin');
        } 
        else {
            redirect("wall");
        }
    }

    public function register() 
    {
        $current_user_id = $this->session->userdata('user_id');
        
        if(!$current_user_id) { 
            $this->load->view('templates/header');
            $this->load->view('users/register');
        } 
        else {
            redirect("wall");
        }
    }

    public function logoff() 
    {
        $this->session->sess_destroy();
        redirect("/");   
    }
    
    public function process_signin() 
    {
        $result = $this->User->validate_signin_form();
        if($result != 'success') {
            $this->session->set_flashdata('input_errors', $result);
            redirect("signin");
        } 
        else 
        {
            $email = $this->input->post('email');
            $user = $this->User->get_user_by_email($email);
            
            $result = $this->User->validate_signin_match($user, $this->input->post('password'));
            
            if($result == "success") 
            {
                $this->session->set_userdata(array('user_id'=>$user['id'], 'first_name'=>$user['first_name']));            
                redirect("signin");
            }
            else 
            {
                $this->session->set_flashdata('input_errors', $result);
                redirect("signin");
            }
        }

    }
    
    public function process_registration() 
    {
        $email = $this->input->post('email');
        $result = $this->User->validate_registration($email);
        
        if($result!=null)
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect("register");
        }
        else
        {
            $form_data = $this->input->post();
            $this->User->create_user($form_data);

            $new_user = $this->User->get_user_by_email($form_data['email']);
            $this->session->set_userdata(array('user_id' => $new_user["id"], 'first_name'=>$new_user['first_name']));
            
            redirect("wall");
        }
    }

}
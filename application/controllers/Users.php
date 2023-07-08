<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        // Load the Security Helper in the constructor
        $this->load->Model('User');
        $this->load->helper('security');
    }
    
    /*  DOCU: This function is triggered by default which displays the sign in/wall page.
        Owner: Karen
    */
    public function index() 
    {
        $current_user_id = $this->session->userdata('user_id');
        if(!$current_user_id) { 
            $this->load->view('templates/header');
            $this->load->view('users/signin');
        } 
        else {
            redirect("/dashboard");
        }

    }
    
    /*  DOCU: This function is triggered to display sign in page if there's no user session yet
        Owner: Karen
    */
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

    /*  DOCU: This function is triggered to display registration page if there's no user session yet.
        Owner: Karen
    */
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

    /*  DOCU: This function logs out the current user then goes to sign in page.
        Owner: Karen
    */
    public function logoff() 
    {
        $this->session->sess_destroy();
        redirect("/");   
    }
    
    /*  DOCU: This function is triggered when the sign in button is clicked. 
        This validates the required form inputs and if user password matches in the database by given email.
        If no problem occured, user will be routed to the Wall page.
        Owner: Karen
    */
    public function process_signin() 
    {
        $this->load->Model('User');
        $result = $this->User->validate_signin_form();
        if($result != 'success') {
            $this->session->set_flashdata('input_errors', $result);
            redirect("signin");
        } 
        else 
        {
            $email = $this->input->post('email');
            $user = $this->user->get_user_by_email($email);
            $password = $this->input->post('password');
            //var_dump(md5($this->input->post('password')));
            //var_dump($user['password']);
            $result = $this->user->validate_signin_match($user, $password);
            //var_dump($user);
            if($result == "success") 
            {
                $this->session->set_userdata(array('user_id'=>$user['user_id'], 'first_name'=>$user['first_name'], 'user_level' => $user['user_level']));          
                //$this->session->set_userdata(array('user_id'=>$user['user_id'], 'first_name'=>$user['first_name']));         
                if($user['user_level'] == 9) 
                {  
                    redirect("dashboard/admin");
                } 
                else if($user['user_level'] == 1) 
                {
                    redirect("/dashboard");
                }   
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
        $result = $this->user->validate_registration($email);
        #var_dump($result);
        if($result!=null)
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect("register");
        }
        else
        {
            $form_data = $this->input->post();
            $this->user->create_user($form_data);
            $new_user = $this->user->get_user_by_email($form_data['email']);
            #var_dump($new_user);
            #var_dump($new_user['user_id']);
            $this->session->set_userdata(array('user_id' => $new_user['user_id'], 'first_name'=>$new_user['first_name']));
            
            if($new_user['user_level'] == 9) 
            {  
                redirect("dashboard/admin");
            } 
            else if($new_user['user_level'] == 1) 
            {
                redirect("/dashboard");
            }   
        }
    }

    public function edit()
    {
        $this->load->view('users/edit');
    } 

    public function process_edit_profile()
    {
        var_dump($this->session->userdata());
        $email = $this->input->post('email');
        $result = $this->user->validate_edit_profile($email);
        var_dump($result);
        if($result!=null)
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect("/users/edit");
        }
        else
        {
            $edit_data = array (
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name')
            );
            $this->User->edit_profile($edit_data);
            redirect('/dashboard');
        }
    }

    public function process_edit_password()
    {
        $result = $this->user->validate_edit_password();
        if($result!=null)
        {
            $this->session->set_flashdata('input_errors', $result);
            redirect("/users/edit");
        }
        else
        {
            $this->User->edit_password();
            redirect('/dashboard');
        }
    }

}
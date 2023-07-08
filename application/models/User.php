<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

    /*  DOCU: This function retrieves user information filtered by email.
        Owner: Karen
    */
    function get_user_by_email($email)
    { 
        $query = "SELECT * FROM Users WHERE email=?";
        return $this->db->query($query, $this->security->xss_clean($email))->row_array();
    }

    function get_all_users()
    {
        $query = "SELECT * FROM Users";
        return $this->db->query($query)->result_array();
    }

    /*  DOCU: This function inserts new user info upon registration.
        Owner: Karen
    */
    function create_user($user)
    {
        //Assign user_level as admin if first user
        $users = $this->user->get_all_users();
        if($users == NULL) {
            $user['user_level'] = 9;
        } else {
            $user['user_level'] = 1;
        }

        $query = "INSERT INTO Users (first_name, last_name, email, password, user_level) VALUES (?,?,?,?,?)";
        $values = array(
            $this->security->xss_clean($user['first_name']), 
            $this->security->xss_clean($user['last_name']), 
            $this->security->xss_clean($user['email']), 
            md5($this->security->xss_clean($user["password"])),
            $this->security->xss_clean($user['user_level'])
        );  
        
        return $this->db->query($query, $values);
    }

    /*  DOCU: This function checks if all required fields were filled up.
        Owner: Karen
    */
    function validate_signin_form() {
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
    
        if(!$this->form_validation->run()) {
            return validation_errors();
        } 
        else {
            return "success";
        }
    }
    
    /*  DOCU: This function contains simple condition to match database record and user input in password.
        Owner: Karen
    */
    function validate_signin_match($user, $password) 
    {
        $hash_password = md5($this->security->xss_clean($password));

        if($user['password'] == $hash_password) {
            return "success";
        }
        else {
            return "Incorrect email/password.";
        }
    }

    /*  DOCU: This function checks required input fields and if unique email.
        Owner: Karen
    */
    function validate_registration($email) 
    {
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');        
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        
        if(!$this->form_validation->run()) {
            return validation_errors();
        }
        else if($this->get_user_by_email($email)) {
            return "Email already taken.";
        }
    }

    function validate_edit_profile($email)
    {
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');   
        $this->form_validation->set_rules('email', 'Email', 'required');   

        if(!$this->form_validation->run()) {
            return validation_errors();
        }
        else if($this->get_user_by_email($email)) {
            return "Email already taken.";
        }
    }

    function edit_profile($edit_data)
    {
        return $this->db->query(
            'UPDATE users SET email = ?, first_name = ?, last_name = ? WHERE user_id = ?', 
            array(
                $this->security->xss_clean($edit_data['email']), 
                $this->security->xss_clean($edit_data['first_name']),
                $this->security->xss_clean($edit_data['last_name']),
                $this->security->xss_clean($this->session->userdata('user_id'))
                )
            );
    }

    function get_password_by_id($old_password)
    {
        $hash_password = md5($this->security->xss_clean($old_password));
        return $this->db->query(
            'SELECT * FROM users WHERE password = ? AND user_id = ?', 
            array(
                $hash_password,
                $this->security->xss_clean($this->session->userdata('user_id'))
                )
            )->row_array();
    }

    function validate_edit_password() 
    {
        $old_password = $this->input->post('old_password');
        $this->form_validation->set_error_delimiters('<div>','</div>');
        $this->form_validation->set_rules('old_password', 'Old Password', 'required|min_length[8]');        
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        var_dump($old_password);
        if(!$this->form_validation->run()) {
            return validation_errors();
        }
        else if(!$this->get_password_by_id($old_password)) {
            return 'Wrong old password';
        }
    }

    function edit_password()
    {
        $password = $this->input->post('password');
        return $this->db->query(
            'UPDATE users SET password = ? WHERE user_id = ?', 
            array(
                md5($this->security->xss_clean($password)),
                $this->security->xss_clean($this->session->userdata('user_id'))
                )
            );
    }

}

?>
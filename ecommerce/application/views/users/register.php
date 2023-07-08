        <div class="error"><?=$this->session->flashdata('input_errors');?></div>

        <h1>Register</h1>

        <?php echo form_open('users/process_registration'); ?>
            First name: <input type="text" name="first_name"><br>
            Last name: <input type="text" name="last_name"><br>
            Email address: <input type="text" name="email"><br>
            Password: <input type="password" name="password"><br>
            Confirm Password: <input type="password" name="confirm_password"><br>
            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
            <input type="submit" value="Register">
            <a href="signin">Already have an account? Log in</a>
        <?php echo form_close(); ?>

    </body>
</html>
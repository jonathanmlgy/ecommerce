
        <div class="error"><?=$this->session->flashdata('input_errors');?></div>

        <h1>Login</h1>
        <form action="signin" method="POST">            
            Email address: <input type="text" name="emails">
            Password: <input type="password" name="password">
        
            <input type="submit" value="Signin">
            <a href="register">Don't have an account? Register</a>
        </form>
        
    </body>
</html>


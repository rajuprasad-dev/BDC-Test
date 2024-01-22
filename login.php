<?php
include_once("./layout/app_layout.php");
app_header("Login");
?>
<div class="bg-container">
    <div class="inner-container">
        <div class="side-form-block">
            <div class="custom-card">
                <div class="custom-card-header mb-4">
                    <h4>Login Form</h4>
                </div>
                <div class="card-body">
                    <form method="POST" id="login-form">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control bg-transparent text-white" id="userName"
                                name="userName" placeholder="Username" required />
                            <label for="userName">Username</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control bg-transparent text-white" id="password"
                                name="password" placeholder="Password" required />
                            <label for="password">Password</label>
                        </div>

                        <span class="d-none alert alert-danger show-form-error">Please enter a valid password</span>

                        <button type="submit" class="btn btn-primary px-4 py-2" id="submit-button"
                            disabled>Login</button>

                        <p class="text-center mt-4"><a href="./register"
                                class="btn btn-link text-white text-decoration-none">Do not have an
                                account with us ? register now</a></p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
app_footer();
?>
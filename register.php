<?php
include_once("./layout/app_layout.php");
app_header("Register");
?>
<div class="bg-container">
    <div class="inner-container">
        <div class="side-form-block">
            <div class="custom-card">
                <div class="custom-card-header mb-4">
                    <h4>Registration Form</h4>
                </div>
                <div class="card-body">
                    <form method="POST" id="registration-form">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control bg-transparent text-white" id="firstName"
                                name="firstName" placeholder="First Name" required />
                            <label for="firstName">First Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control bg-transparent text-white" id="lastName"
                                name="lastName" placeholder="Last Name" required />
                            <label for="lastName">Last Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control bg-transparent text-white" id="userName"
                                name="userName" placeholder="Username" required />
                            <label for="userName">Username</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control bg-transparent text-white" id="email" name="email"
                                placeholder="Email address" required />
                            <label for="email">Email address</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control bg-transparent text-white" id="phone" name="phone"
                                placeholder="Phone" required />
                            <label for="phone">Phone</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control bg-transparent text-white" id="password"
                                name="password" placeholder="Password" required />
                            <label for="password">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control bg-transparent text-white" id="confirmPassword"
                                name="confirmPassword" placeholder="Confirm Password" required />
                            <label for="confirmPassword">Confirm Password</label>
                        </div>

                        <span class="d-none alert alert-danger show-form-error">Password and confirm password should
                            be same</span>

                        <button type="submit" class="btn btn-primary px-4 py-2" id="submit-button"
                            disabled>Register</button>

                        <p class="text-center mt-4"><a href="./login"
                                class="btn btn-link text-white text-decoration-none">Already have an
                                account with us ? login now</a></p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
app_footer();
?>
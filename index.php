<?php
include_once("./layout/app_layout.php");
app_header("Dashboard");
?>
<div id="dashboard" class="container">
    <div class="main-content">
        <table class="table table-responsive table-bordered table-dark w-100">
            <tbody>
                <tr>
                    <td>First Name</td>
                    <td id="firstName"></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td id="lastName"></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td id="username"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td id="email"></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td id="phone"></td>
                </tr>
            </tbody>
        </table>

        <button class="logout-btn btn btn-danger px-4 py-2 mt-5" id="logout-btn">Logout</button>
    </div>
</div>
<?php
app_footer();
?>
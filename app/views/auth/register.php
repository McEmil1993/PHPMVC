<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration Page</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/bootstrap/4.5.2/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/plugins/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/plugins/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/iCheck/square/blue.css">

</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="/index2.html"><b>Admin</b>LTE</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">Register a new membership</p>

            <form id="registrationForm" action="#" method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="First name" name="firstname" id="firstname">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Middle name(optional)" name="middlename"
                        id="middlename">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Last name" name="lastname" id="lastname">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="number" class="form-control" placeholder="Contact" name="contact" id="contact">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username" name="username" id="username">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Retype password" name="confirm_password"
                        id="confirm_password">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="/login" class="text-center">I already have a membership</a>
        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->

    <!-- jQuery 2.2.3 -->
    <script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <script>
    $(function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    </script>
    <script>
    $("#registrationForm").submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Get input field values
        var firstName = $("#firstname").val();
        var middleName = $("#middlename").val();
        var lastName = $("#lastname").val();
        var contact = $("#contact").val();
        var username = $("#username").val();
        var password = $("#password").val();
        var confirmPassword = $("#confirm_password").val();

        // Validate parameters
        if (!firstName || !lastName || !contact || !username || !password || !confirmPassword) {
            alert("Please fill in all fields.");
            return;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return;
        }

        // Check if username contains a combination of letters and numbers
        var hasLettersAndNumbers = /[a-zA-Z].*[0-9]|[0-9].*[a-zA-Z]/.test(username);

        console.log("Username:", username);
        console.log("Contains letters and numbers:", hasLettersAndNumbers);

        // Handle the condition based on the check
        if (!hasLettersAndNumbers) {
            alert("Username must contain a combination of letters and numbers.");
            return;
        }

        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '/register',
            data: {
                firstname: firstName,
                middlename: middleName,
                lastname: lastName,
                contact: contact,
                username: username,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                console.log('response', response);
                alert(response.message);
                if (response.status == 1 ) {
                    location.reload();
                }
            },
            error: function(err) {
                console.log('err', err);
                alert("Registration failed. Please try again later.");
            }
        });
    });
    </script>
</body>

</html>
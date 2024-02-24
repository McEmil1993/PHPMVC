<?php ob_start(); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $data['title']  ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $data['title']  ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <button type="button" class="btn btn-primary btn-sm new_owner">New user</button>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Full name</th>
                                    <th>User type</th>
                                    <th>Contact</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['datas'] as $user): ?>
                                <tr>
                                    <td><?=$user['firstname'] .' '.$user['middlename'].' '.$user['lastname'] ?></td>
                                    <td> <?= $user['role']  ?></td>
                                    <td> <?= $user['contact']  ?></td>
                                    <td style="text-align: center;"><small
                                            class="label pull-center <?= $user['is_deleted'] == 0 ? 'bg-green':'bg-red'  ?>"><?= $user['is_deleted'] == 0 ? 'Active':'Deleted'  ?></small>
                                    </td>
                                    <td style="text-align: center;">
                                        <!-- Apply text-align:center to center the buttons -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info edit_owner"
                                                data-accoount_id="<?= $user['account_id']  ?>"
                                                data-admin_id="<?= $user['admin_id']  ?>">Edit</button>
                                            <button type="button" class="btn btn-sm  btn-danger delete_owner"
                                                data-accoount_id="<?= $user['account_id']  ?>"
                                                data-admin_id="<?= $user['admin_id']  ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php  endforeach ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<div id="default_modal" class="modal fade" role="dialog">
    <div class="toast-container err"></div>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New User</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="First name" name="firstname"
                                id="firstname">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Middle name(optional)"
                                name="middlename" id="middlename">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Last name" name="lastname"
                                id="lastname">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="number" class="form-control" placeholder="Contact" name="contact" id="contact">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Username" name="username"
                                id="username">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="role" id="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Password" name="password"
                                id="password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Retype password"
                                name="confirm_password" id="confirm_password">
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <!-- /.modal-dialog -->
</div>


<script>
$('#users').addClass('active');
$('.new_owner').click(function() {
    $('#default_modal').modal('show');
});
$('.edit_owner').click(function() {
    var admin_id = $(this).attr('data-admin_id');
    var account_id = $(this).attr('data-account_id');

    $.ajax({
        type: "POST",
        url: "/edit-user", // Replace this with the URL to your server-side script
        dataType: 'json',
        data: {
            admin_id: admin_id,
            account_id: account_id// Get the selected role
        },
        success: function(response) {
            showSuccessToast(response)
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

$('.delete_owner').click(function() {
    var admin_id = $(this).attr('data-admin_id');
    var account_id = $(this).attr('data-account_id');
    // console.log($(this).attr('data-admin_id'));
});
</script>
<script>
$("button.save").click(function() {
    var firstname = $("#firstname").val();
    var middlename = $("#middlename").val();
    var lastname = $("#lastname").val();
    var contact = $("#contact").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var confirm_password = $("#confirm_password").val();

    // Validation
    if (firstname === '' || lastname === '' || contact === '' || username === '' || password ===
        '' || confirm_password === '') {
        showErrorToast("Please fill in all fields.")

        return false;
    }

    if (contact.length !== 11) {
        showErrorToast("Contact number must be 11 digits.")
        return false;
    }

    if (password !== confirm_password) {
        showErrorToast("Passwords do not match.")
        return false;
    }

    // AJAX call to submit the form if all validations pass
    $.ajax({
        type: "POST",
        url: "/add-user", // Replace this with the URL to your server-side script
        dataType: 'json',
        data: {
            firstname: firstname,
            middlename: middlename,
            lastname: lastname,
            contact: contact,
            username: username,
            password: password,
            role: $("#role").val() // Get the selected role
        },

        success: function(response) {
            // Handle the response from the server
            if (response.status == 0) {

                // alert(response.message);
                // showSuccessToast()
                showErrorToast(response.message)
                $("#username").val('')
                return;
            }
            console.log(response);
            $("input[type=text], input[type=password], input[type=number]").val('');
            $('#default_modal').modal('hide');
            showSuccessToast(response.message)
            // Optionally, you can close the modal or redirect the user to another page here
        },
        error: function(xhr, status, error) {
            // Handle errors here
            console.error(xhr.responseText);
            alert("An error occurred while submitting the form. Please try again.");
        }
    });
});
</script>
<?php
 $content = ob_get_clean(); 
 $title = $data['title'];
 ?>
<?php require_once '../app/views/layouts/main.php'; ?>
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
                        <button type="button" class="btn btn-primary btn-sm new-user">New user</button>

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
                                    <td> <?= ucfirst($user['role']);  ?></td>
                                    <td> <?= $user['contact']  ?></td>
                                    <td style="text-align: center;"><small
                                            class="label pull-center <?= $user['is_deleted'] == 0 ? 'bg-green':'bg-red'  ?>"><?= $user['is_deleted'] == 0 ? 'Active':'Deleted'  ?></small>
                                    </td>
                                    <td style="text-align: center;">
                                        <!-- Apply text-align:center to center the buttons -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info eidt-user"
                                                data-account="<?= $user['account_id']  ?>"
                                                data-admin_id="<?= $user['admin_id']  ?>">Edit</button>
                                            <button type="button" class="btn btn-sm  btn-danger delete-owner"
                                                data-account="<?= $user['account_id']  ?>"
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

<!--  Edit Modal  -->

<div id="edit_modal" class="modal fade" role="dialog">
    <div class="toast-container err"></div>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <div class="modal-title">
                    <div class="row">
                        <div class="col-sm-2">
                            <h4>Edit User</h4>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control " name="is_deleted" id="is_deleted">
                                <option value="0">Active</option>
                                <option value="1">Deleted</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_admin_id" value="">
                <input type="hidden" id="edit_account_id" value="">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="First name" name="firstname"
                                id="edit_firstname">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Middle name(optional)"
                                name="middlename" id="edit_middlename">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Last name" name="lastname"
                                id="edit_lastname">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="number" class="form-control" placeholder="Contact" name="contact"
                                id="edit_contact">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Username" name="username"
                                id="edit_username">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="role" id="edit_role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Password" name="password"
                                id="edit_password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Retype password"
                                name="confirm_password" id="edit_confirm_password">
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <!-- /.modal-dialog -->
</div>

<script>
$('#users').addClass('active');
$('.new-user').click(function() {
    $('#default_modal').modal('show');
});
$('.eidt-user').click(function() {
    var admin_id = $(this).attr('data-admin_id');
    var account_id = $(this).attr('data-account');

    var firstname = $("#edit_firstname");
    var middlename = $("#edit_middlename");
    var lastname = $("#edit_lastname");
    var contact = $("#edit_contact");
    var username = $("#edit_username");
    var role = $("#edit_role");
    var is_deleted = $("#is_deleted");
    $("#edit_admin_id").val(admin_id);
    $("#edit_account_id").val(account_id);

    $.ajax({
        type: "POST",
        url: "/edit-user", // Replace this with the URL to your server-side script
        dataType: 'json',
        data: {
            admin_id: admin_id,
            account_id: account_id // Get the selected role
        },
        success: function(response) {
            firstname.val(response.firstname);
            middlename.val(response.middlename);
            lastname.val(response.lastname);
            contact.val(response.contact);
            username.val(response.username);
            role.val(response.role);
            is_deleted.val(response.is_deleted);
            if (response.is_deleted == 0) {
                $('#is_deleted').removeClass('text-red');
                $('#is_deleted').addClass('text-green');
            } else {
                $('#is_deleted').removeClass('text-green');
                $('#is_deleted').addClass('text-red');
            }

            $('#edit_modal').modal('show')
            // showSuccessToast(response)
         
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

$('.delete-owner').click(function() {
    var admin_id = $(this).attr('data-admin_id');
    var account_id = $(this).attr('data-account_id');
    // console.log($(this).attr('data-admin_id'));
});
$('#is_deleted').change(function() {
    if ($(this).val() == '0') {
        $(this).removeClass('text-red');
        $(this).addClass('text-green');
    } else {
        $(this).removeClass('text-green');
        $(this).addClass('text-red');
    }

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


$("button.edit").click(function() {
    var firstname = $("#edit_firstname").val();
    var middlename = $("#edit_middlename").val();
    var lastname = $("#edit_lastname").val();
    var contact = $("#edit_contact").val();
    var username = $("#edit_username").val();
    var password = $("#edit_password").val();
    var confirm_password = $("#edit_confirm_password").val();
    var role = $("#edit_role").val();
    var is_deleted = $("#is_deleted").val();

    var admin_id = $("#edit_admin_id").val();
    var account_id = $("#edit_account_id").val();



    // Validation
    if (firstname === '' || lastname === '' || contact === '' || username === '') {
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
        url: "/update-user", // Replace this with the URL to your server-side script
        dataType: 'json',
        data: {
            firstname: firstname,
            middlename: middlename,
            lastname: lastname,
            contact: contact,
            username: username,
            password: password,
            role: role,
            is_deleted: is_deleted,
            admin_id: admin_id,
            account_id: account_id
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

            $("input[type=text], input[type=password], input[type=number]").val('');
            $('#edit_modal').modal('hide');

            showSuccessToast(response.message)

            setTimeout(function() {
                window.location.reload();

            }, 900);



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
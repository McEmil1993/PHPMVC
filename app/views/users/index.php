<?php
ob_start();
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Users Management</h1>
    <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Users</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <button type="button" class="btn btn-primary btn-sm" id="open-create-user-modal">
              Add New User
            </button>
          </div>
          <div class="box-body">
            <table class="table table-bordered table-striped" id="users-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Created</th>
                  <th style="width: 180px;">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="create-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="create-user-form" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add New User</h4>
        </div>
        <div class="modal-body">
          <div class="form-group" data-field="name">
            <label for="create-user-name">Name</label>
            <input type="text" class="form-control" id="create-user-name" name="name" placeholder="Enter full name">
            <span class="help-block" style="display:none;"></span>
          </div>
          <div class="form-group" data-field="email">
            <label for="create-user-email">Email</label>
            <input type="email" class="form-control" id="create-user-email" name="email" placeholder="Enter email">
            <span class="help-block" style="display:none;"></span>
          </div>
          <div class="form-group" data-field="password">
            <label for="create-user-password">Password</label>
            <input type="password" class="form-control" id="create-user-password" name="password" placeholder="Enter password">
            <span class="help-block" style="display:none;"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="edit-user-form" novalidate>
        <input type="hidden" id="edit-user-id" name="id">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Edit User</h4>
        </div>
        <div class="modal-body">
          <div class="form-group" data-field="name">
            <label for="edit-user-name">Name</label>
            <input type="text" class="form-control" id="edit-user-name" name="name" placeholder="Enter full name">
            <span class="help-block" style="display:none;"></span>
          </div>
          <div class="form-group" data-field="email">
            <label for="edit-user-email">Email</label>
            <input type="email" class="form-control" id="edit-user-email" name="email" placeholder="Enter email">
            <span class="help-block" style="display:none;"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        <p id="delete-user-message">Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-user">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
  $('#users').addClass('active');

  (function () {
    var users = [];
    var dataTable = null;
    var usersTableSelector = '#users-table';
    var loadUsersRequest = null;
    var pendingDeleteUserId = null;

    function escapeHtml(value) {
      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function showToastMessage(message, type) {
      if (type === 'success' && typeof window.showSuccessToast === 'function') {
        window.showSuccessToast(message);
        return;
      }

      if (type === 'error' && typeof window.showErrorToast === 'function') {
        window.showErrorToast(message);
        return;
      }

      window.alert(message);
    }

    function resetFormState(formSelector) {
      var $form = $(formSelector);
      $form.find('.form-group').removeClass('has-error');
      $form.find('.form-control').removeClass('invalid');
      $form.find('.help-block').text('').hide();
    }

    function setFieldError(formSelector, field, message) {
      var $group = $(formSelector + ' .form-group[data-field="' + field + '"]');
      $group.addClass('has-error');
      $group.find('.form-control').addClass('invalid');
      $group.find('.help-block').text(message).show();
    }

    function validateCreateUser(payload) {
      var errors = {};

      if (!payload.name) {
        errors.name = 'Name is required';
      }

      if (!payload.email) {
        errors.email = 'Email is required';
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(payload.email)) {
        errors.email = 'Email is invalid';
      }

      if (!payload.password) {
        errors.password = 'Password is required';
      } else if (payload.password.length < 6) {
        errors.password = 'Password must be at least 6 characters';
      }

      return errors;
    }

    function validateEditUser(payload) {
      var errors = {};

      if (!payload.name) {
        errors.name = 'Name is required';
      }

      if (!payload.email) {
        errors.email = 'Email is required';
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(payload.email)) {
        errors.email = 'Email is invalid';
      }

      return errors;
    }

    function applyApiValidationErrors(formSelector, result) {
      var errors = result &&
        result.data &&
        result.data.data &&
        result.data.data.errors &&
        typeof result.data.data.errors === 'object'
        ? result.data.data.errors
        : {};

      Object.keys(errors).forEach(function (field) {
        setFieldError(formSelector, field, errors[field]);
      });

      return Object.keys(errors).length > 0;
    }

    function renderUsersTable() {
      var tbody = document.querySelector(usersTableSelector + ' tbody');

      if (dataTable) {
        dataTable.destroy();
        dataTable = null;
      }

      tbody.innerHTML = '';

      users.forEach(function (user) {
        var tr = document.createElement('tr');
        tr.innerHTML =
          '<td>' + escapeHtml(user.id) + '</td>' +
          '<td>' + escapeHtml(user.name) + '</td>' +
          '<td>' + escapeHtml(user.email) + '</td>' +
          '<td>' + escapeHtml(user.created_at || '') + '</td>' +
          '<td>' +
            '<button type="button" class="btn btn-xs btn-info js-edit-user" data-id="' + escapeHtml(user.id) + '">Edit</button> ' +
            '<button type="button" class="btn btn-xs btn-danger js-delete-user" data-id="' + escapeHtml(user.id) + '" data-name="' + escapeHtml(user.name) + '">Delete</button>' +
          '</td>';
        tbody.appendChild(tr);
      });
      if (window.jQuery && jQuery.fn && jQuery.fn.DataTable) {
        dataTable = jQuery(usersTableSelector).DataTable({
          ordering: true,
          autoWidth: false
        });
      }
    }

    function loadUsers() {
      loadUsersRequest = fetch('/api/users', {
        headers: {
          'Accept': 'application/json'
        }
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (payload) {
          if (!payload || payload.success !== true || !Array.isArray(payload.data)) {
            throw new Error(payload && payload.message ? payload.message : 'Failed to load users');
          }

          users = payload.data.slice();
          renderUsersTable();
          return users;
        })
        .catch(function (error) {
          var tbody = document.querySelector(usersTableSelector + ' tbody');
          tbody.innerHTML = '<tr><td colspan="5">Failed to load users.</td></tr>';
          showToastMessage(error.message || 'Failed to load users', 'error');
          throw error;
        });

      return loadUsersRequest;
    }

    $('#open-create-user-modal').on('click', function () {
      document.getElementById('create-user-form').reset();
      resetFormState('#create-user-form');
      $('#create-user-modal').modal('show');
    });

    $(document).on('click', '.js-edit-user', function () {
      var userId = String($(this).data('id'));
      var user = users.find(function (item) {
        return String(item.id) === userId;
      });

      if (!user) {
        showToastMessage('User not found', 'error');
        return;
      }

      resetFormState('#edit-user-form');
      $('#edit-user-id').val(user.id);
      $('#edit-user-name').val(user.name || '');
      $('#edit-user-email').val(user.email || '');
      $('#edit-user-modal').modal('show');
    });

    $(document).on('click', '.js-delete-user', function () {
      pendingDeleteUserId = String($(this).data('id'));
      var userName = String($(this).data('name') || 'this user');

      $('#delete-user-message').text('Soft delete ' + userName + '?');
      $('#delete-user-modal').modal('show');
    });

    $('#confirm-delete-user').on('click', function () {
      if (!pendingDeleteUserId) {
        $('#delete-user-modal').modal('hide');
        return;
      }

      fetch('/api/users/' + encodeURIComponent(pendingDeleteUserId), {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json'
        }
      })
        .then(function (response) {
          return response.json().then(function (data) {
            return {
              ok: response.ok,
              status: response.status,
              data: data
            };
          });
        })
        .then(function (result) {
          if (!result.ok || !result.data || result.data.success !== true) {
            throw new Error(result.data && result.data.message ? result.data.message : 'Failed to delete user');
          }

          return loadUsers().then(function () {
            pendingDeleteUserId = null;
            $('#delete-user-modal').modal('hide');
            showToastMessage(result.data.message || 'User deleted', 'success');
          });
        })
        .catch(function (error) {
          pendingDeleteUserId = null;
          $('#delete-user-modal').modal('hide');
          showToastMessage(error.message || 'Failed to delete user', 'error');
        });
    });

    $('#delete-user-modal').on('hidden.bs.modal', function () {
      pendingDeleteUserId = null;
    });

    $('#create-user-form').on('submit', function (event) {
      event.preventDefault();
      resetFormState('#create-user-form');

      var payload = {
        name: ($('#create-user-name').val() || '').trim(),
        email: ($('#create-user-email').val() || '').trim(),
        password: ($('#create-user-password').val() || '').trim()
      };

      var errors = validateCreateUser(payload);
      Object.keys(errors).forEach(function (field) {
        setFieldError('#create-user-form', field, errors[field]);
      });

      if (Object.keys(errors).length > 0) {
        showToastMessage('Please fix the highlighted fields', 'error');
        return;
      }

      fetch('/api/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
        .then(function (response) {
          return response.json().then(function (data) {
            return {
              ok: response.ok,
              status: response.status,
              data: data
            };
          });
        })
        .then(function (result) {
          if (!result.ok || !result.data || result.data.success !== true) {
            if (result.status === 422 && applyApiValidationErrors('#create-user-form', result)) {
              throw new Error('Please fix the highlighted fields');
            }
            throw new Error(result.data && result.data.message ? result.data.message : 'Failed to create user');
          }

          return loadUsers().then(function () {
            $('#create-user-modal').modal('hide');
            document.getElementById('create-user-form').reset();
            resetFormState('#create-user-form');
            showToastMessage(result.data.message || 'User created', 'success');
          });
        })
        .catch(function (error) {
          showToastMessage(error.message || 'Failed to create user', 'error');
        });
    });

    $('#edit-user-form').on('submit', function (event) {
      event.preventDefault();
      resetFormState('#edit-user-form');

      var userId = ($('#edit-user-id').val() || '').trim();
      var payload = {
        name: ($('#edit-user-name').val() || '').trim(),
        email: ($('#edit-user-email').val() || '').trim()
      };

      var errors = validateEditUser(payload);
      Object.keys(errors).forEach(function (field) {
        setFieldError('#edit-user-form', field, errors[field]);
      });

      if (Object.keys(errors).length > 0) {
        showToastMessage('Please fix the highlighted fields', 'error');
        return;
      }

      fetch('/api/users/' + encodeURIComponent(userId), {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
        .then(function (response) {
          return response.json().then(function (data) {
            return {
              ok: response.ok,
              status: response.status,
              data: data
            };
          });
        })
        .then(function (result) {
          if (!result.ok || !result.data || result.data.success !== true) {
            if (result.status === 422 && applyApiValidationErrors('#edit-user-form', result)) {
              throw new Error('Please fix the highlighted fields');
            }
            throw new Error(result.data && result.data.message ? result.data.message : 'Failed to update user');
          }

          return loadUsers().then(function () {
            $('#edit-user-modal').modal('hide');
            resetFormState('#edit-user-form');
            showToastMessage(result.data.message || 'User updated', 'success');
          });
        })
        .catch(function (error) {
          showToastMessage(error.message || 'Failed to update user', 'error');
        });
    });

    loadUsers();
  })();
</script>

<?php
$content = ob_get_clean();
$title = 'Users Management';
require BASE_PATH . '/app/views/layouts/main.php';
?>

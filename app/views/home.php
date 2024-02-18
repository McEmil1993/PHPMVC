<h2>Welcome to the Home Page</h2>

<?= isset($_SESSION['account_id']) ? $_SESSION['account_id'] :'' ?>
<br>
<?= isset($_SESSION['firstname']) ? $_SESSION['firstname'] :'' ?>
<br>
<?= isset($_SESSION['middlename'])  ? $_SESSION['middlename'] :'' ?>
<br>
<?= isset($_SESSION['lastname']) ? $_SESSION['lastname'] :'' ?>
<br>
<?= isset($_SESSION['role']) ? $_SESSION['role'] :'' ?>
<br>
<?=$data['title'] ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<button id="logoutButton">Logout</button>

<script>
$(document).ready(function() {
    $("#logoutButton").click(function() {
        // Send AJAX request to logout API
        $.ajax({
            type: 'GET',
            url: '/logout', // Replace '/logout' with your actual logout API endpoint
            dataType: 'json',
            success: function(response) {
                
            }
        });

        window.location.reload();
    });
});
</script>
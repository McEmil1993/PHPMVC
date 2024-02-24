<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> <?= $title ?></title>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/plugins/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">


    <!-- jQuery 2.2.3 -->
    <script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="/plugins/dist/js/app.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="/plugins/dist/js/demo.js"></script>


    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- page script -->
    <style>
    .toast {
        position: fixed;
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        display: none;
        z-index: 9999;
    }

    .toast.green {
        background-color: #4CAF50;
        /* Green */
    }

    .toast.red {
        background-color: #f44336;
        /* Red */
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in-out forwards;
    }

    .fade-out {
        animation: fadeOut 0.5s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }
    </style>

    <script>
    function showSuccessToast(message) {
        showToast(message, 'success');
    }

    function showErrorToast(message) {
        showToast(message, 'error');
    }

    function showToast(message, type) {
        var toast = $('<div class="toast"></div>').text(message);

        if (type === 'success') {
            toast.addClass('green');
            $('.toast-container.suc').append(toast);
        } else if (type === 'error') {
            toast.addClass('red');
            $('.toast-container.err').append(toast);
        }

        toast.addClass('fade-in').fadeIn();

        setTimeout(function() {
            toast.removeClass('fade-in').addClass('fade-out');
            setTimeout(function() {
                toast.remove();
            }, 500);
        }, 2000);
    }
    </script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php require_once 'header.php'; ?>
        <?php require_once 'sidenav.php'; ?>

        <div id="content">
            <?= $content ?>
        </div>

        <?php require_once 'footer.php'; ?>

        <div class="toast-container suc"></div>
</body>
<!-- ./wrapper -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    var logoutButton = document.getElementById("logoutButton");
    logoutButton.addEventListener("click", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "/logout", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle success response if needed
            }
        };
        xhr.send();

        window.location.reload();
    });
});
</script>

</html>
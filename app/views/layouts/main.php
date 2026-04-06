<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap/4.5.2/css/font-awesome.min.css">
    <link rel="stylesheet" href="/plugins/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/plugins/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/plugins/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="/plugins/fastclick/fastclick.js"></script>
    <script src="/plugins/dist/js/app.min.js"></script>
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
        <div class="toast-container err"></div>
    </div>
</body>

</html>

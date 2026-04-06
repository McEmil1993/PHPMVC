<header class="main-header">
    <a href="/home" class="logo">
        <span class="logo-mini"><b>A</b></span>
        <span class="logo-lg"><b>Admin</b> Panel</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/plugins/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <span><?= isset($_SESSION['account_id']) ? trim($_SESSION['firstname'] . ' ' . $_SESSION['lastname']) : 'User' ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="/plugins/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                            <p>
                                <?= isset($_SESSION['account_id']) ? trim($_SESSION['firstname'] . ' ' . $_SESSION['lastname']) : '' ?>
                                <small>Admin</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

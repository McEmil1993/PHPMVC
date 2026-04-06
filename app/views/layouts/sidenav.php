<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/plugins/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= isset($_SESSION['account_id']) ? $_SESSION['firstname'] .' '. $_SESSION['lastname'] :'' ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <ul class="sidebar-menu">
        <li id="dashboard">
          <a href="/home">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li id="users">
          <a href="/users">
            <i class="fa fa-users"></i> <span>Users Management</span>
          </a>
        </li>
      </ul>
    </section>
  </aside>

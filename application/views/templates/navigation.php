<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Time Tracker</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">
        <li <?php if($this->uri->segment(1) == 'dashboard'): ?> class="active" <?php endif; ?>><a href="/dashboard">Dashboard</a></li>
        <li <?php if($this->uri->segment(1) == 'user'): ?> class="active" <?php endif; ?>><a href="/user">Users</a></li>
        <li <?php if($this->uri->segment(1) == 'employee'): ?> class="active" <?php endif; ?>><a href="/employee">Employees</a></li>
        <li <?php if($this->uri->segment(1) == 'timerecord'): ?> class="active" <?php endif; ?>><a href="/timerecord">Time Record</a></li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->user_name; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/authenticate/logout">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
<style>
  .notification-scroll .unread {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
}

.notification-scroll .read {
    opacity: 0.7;
}

.mark-as-read {
    padding: 0;
    color: var(--bs-primary);
}

.mark-as-read:hover {
    color: var(--bs-primary-darker);
}
</style>
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark dark:text-white" href="adminuser.php">Admin</a></li>
            <li class="breadcrumb-item text-sm text-dark dark:text-white active" aria-current="page">
              <?php
                // Get the current PHP filename without extension
                $current_file = basename($_SERVER['PHP_SELF'], '.php');
                
                $page_titles = [
                  'adminuser' => 'Account Management+',
                  'mapping' => 'Map Management',
                  'decease' => 'Deceased Records',
                  'soa' => 'SOA',
                  'payment' => 'Payment Management',
                  'profile' => 'Profile',
                  'customer' => 'Customer Management',
                  'index' => 'Dashboard'
                ];
                echo $page_titles[$current_file] ?? ucfirst($current_file);
              ?>
            </li>
          </ol>
        </nav>
        
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <ul class="navbar-nav ms-md-auto justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0">
                <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i>
              </a>
            </li>
            <li class="nav-item dropdown pe-3">
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">notifications</i>
                <span class="notification-badge position-absolute translate-middle badge rounded-pill bg-danger">
                    0
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                  <div class="d-flex justify-content-between align-items-center px-3">
                      <h6 class="text-sm mb-0">Notifications</h6>
                      <button class="btn btn-link btn-sm" onclick="notificationManager.markAllAsRead()">
                          Mark all as read
                      </button>
                  </div>
                  <div class="dropdown-divider"></div>
                  <div id="notification-container" class="notification-scroll" style="max-height: 300px; overflow-y: auto;">
                      <!-- Notifications will be inserted here -->
                  </div>
                  <div class="text-center mt-2" id="no-notifications" style="display: none;">
                      <p class="text-xs text-secondary mb-0">No new notifications</p>
                  </div>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
    var body = document.getElementsByTagName('body')[0];
    var className = 'g-sidenav-pinned';

    iconNavbarSidenav.addEventListener('click', function() {
        if (body.classList.contains(className)) {
            body.classList.remove(className);
        } else {
            body.classList.add(className);
        }
    });
});
</script>
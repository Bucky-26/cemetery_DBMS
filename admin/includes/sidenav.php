<?php
// Update current page detection to work with clean URLs
$request_uri = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($request_uri, PHP_URL_PATH));
?>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="../admin/index.php">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Garden of Life Eternal</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
      <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Reports</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="/admin/index.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <!-- Cemetery Management -->
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Cemetery Management</h6>
        </li>
<li class="nav-item">
  <a class="nav-link <?php echo ($current_page == 'mapping.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="mapping.php">
    <i class="material-symbols-rounded opacity-5">map</i>
Map  </a>
</li>
  </a>
</li>
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'decease.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="decease.php">
<i class="material-symbols-rounded opacity-5">history_edu</i>
Burial Records  </a>
</li>
<!-- Plot Management--> 

<!-- Cemetery Management End -->

<!-- Customer Management-->

<li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Customer Management</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'customer.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="customer.php">
            <i class="material-symbols-rounded opacity-5">group</i>
            <span class="nav-link-text ms-1">Customer</span>
          </a>
          <a class="nav-link <?php echo ($current_page == 'contract.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="contract.php">
            <i class="material-symbols-rounded opacity-5">group</i>
            <span class="nav-link-text ms-1">Contract</span>
          </a>
        </li>


<!-- Customer Management End -->
<!-- Billing Management  -->

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Billing Management</h6>
        </li>
      
<!--Statment OF Account-->

        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'soa.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="soa.php">
            <i class="material-symbols-rounded opacity-5">account_balance_wallet</i>
            <span class="nav-link-text ms-1">SOA</span>
          </a>
        </li>


<!--Statment Of Account End-->
<!--Payment Management-->

<li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'payment.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="payment.php">
            <i class="material-symbols-rounded opacity-5">credit_card </i>
            <span class="nav-link-text ms-1">Payment Management</span>
          </a>
        </li>

<!--Payment Management End-->

<!-- Billing Management End -->

<!-- Account Management--> 
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account Management</h6>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'adminuser.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="adminuser.php">
            <i class="material-symbols-rounded opacity-5">manage_accounts</i>
            <span class="nav-link-text ms-1">Accounts</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'emply.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="emply.php">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Employee</span>
          </a>
        </li>

<!--Profile-->
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'profile.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>" href="profile.php">
<i class="material-symbols-rounded">account_circle</i>
Profile
  </a>
</li>


        <li class="nav-item">
          <a class="nav-link text-dark" href="model/logout.php">
            <i class="material-symbols-rounded opacity-5" >logout</i>
            <span class="nav-link-text ms-1">Logout</span>
          </a>
        </li>
    
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      
    </div>
  </aside>

  
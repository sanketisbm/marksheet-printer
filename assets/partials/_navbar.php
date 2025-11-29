<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="index.php"><img src="assets/images/logo.jpg" class="mr-2"
        alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/favicon.png" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">

          <?php if (empty($_SESSION['profile_picture'])) {
            echo '<img src="assets/images/face28.jpg" alt="profile" />';
          } else {
            $base64_image = $_SESSION['profile_picture'];
            $image_data = base64_decode($base64_image);
            echo '<img src="data:image/jpg;base64,' . base64_encode($image_data) . '" alt="Profile Picture">';
          } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <a href="profile" class="dropdown-item">
            <i class="ti-settings text-primary"></i>
            Profile
          </a>
          <a href="logout" class="dropdown-item">
            <i class="ti-power-off text-primary"></i>
            Logout
          </a>
        </div>
      </li>
      <li class="nav-item nav-settings d-none d-lg-flex">
        <?php echo $_SESSION['employee_name']; ?>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
      data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>
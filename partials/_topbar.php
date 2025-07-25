<?php
include '_session.php';
require_once __DIR__ . '/../includes/config.php';


$stmt = $dbh->prepare("SELECT fName, lName FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $fullName = $user['fName'] . ' ' . $user['lName'];
} else {
    $fullName = 'Guest';
}
?>
<nav
    class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button
        id="sidebarToggleTop"
        class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <img src="img/logo.svg"
        alt="Site Logo"
        class="d-none d-sm-inline-block align-middle me-2"
        style="height: 60px;">
    <h4 class="d-none d-sm-inline-block align-middle mb-0 me-2 text-dark ml-2">
        Royal Crown Real Estate Ltd.
    </h4>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a
                class="nav-link dropdown-toggle"
                href="#"
                id="userDropdown"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $fullName; ?></span>
                <img
                    class="img-profile rounded-circle"
                    src="img/undraw_profile.svg" />
            </a>
            <!-- Dropdown - User Information -->
            <div
                class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a
                    class="dropdown-item"
                    href="#"
                    data-toggle="modal"
                    data-target="#logoutModal">
                    <i
                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
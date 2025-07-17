<?php
require_once __DIR__ . '/_session.php';
require_once __DIR__ . '/../includes/config.php';

$stmt = $dbh->prepare("SELECT userType FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$userTypes = $user['userType'];
if ($userTypes == 'superAdmin') {
    $userType = "Super Admin";
} else {
    $userType = 'Admin';
}
?>
<ul
    class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
    id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a
        class="sidebar-brand d-flex align-items-center justify-content-center"
        href="dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?php echo $userType; ?></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="Dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider" />

    <?php if ($userType === 'Super Admin'): ?>
        <!-- Heading -->
        <div class="sidebar-heading">User Management</div>
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-toggle="collapse"
                data-target="#collapseUser"
                aria-expanded="true"
                aria-controls="collapseUser">
                <i class="fas fa-user-cog"></i>
                <span>User</span>
            </a>
            <div
                id="collapseUser"
                class="collapse"
                aria-labelledby="headingUser"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">User Components :</h6>
                    <a class="collapse-item" href="userRegister.php">Add User</a>
                    <a class="collapse-item" href="manageUser.php">Manage Users</a>
                </div>
            </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider" />
    <?php endif; ?>

    <!-- Heading -->
    <div class="sidebar-heading">Business Investor & Funding</div>
    <li class="nav-item">
        <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseInvestor"
            aria-expanded="true"
            aria-controls="collapseInvestor">
            <i class="fas fa-user-tie"></i>
            <span>Investor</span>
        </a>
        <div
            id="collapseInvestor"
            class="collapse"
            aria-labelledby="headingUser"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Investor Components :</h6>
                <a class="collapse-item" href="addInvestor.php">Add Investor</a>
                <a class="collapse-item" href="manageInvestor.php">Manage Investors</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseFunding"
            aria-expanded="true"
            aria-controls="collapseFunding">
            <i class="fas fa-hand-holding-usd"></i>
            <span>Funding</span>
        </a>
        <div
            id="collapseFunding"
            class="collapse"
            aria-labelledby="headingUser"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Funding Components :</h6>
                <a class="collapse-item" href="AddFunding.php">Add Fund</a>
                <a class="collapse-item" href="fundingReport.php">Manage fund</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->
    <div class="sidebar-heading">Building Sales Manager</div>
    <li class="nav-item">
        <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseLayout"
            aria-expanded="true"
            aria-controls="collapseLayout">
            <i class="fas fa-building"></i>
            <span>Building</span>
        </a>
        <div
            id="collapseLayout"
            class="collapse"
            aria-labelledby="headingUser"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Building Components :</h6>
                <a class="collapse-item" href="addBuilding.php">Add Building</a>
                <a class="collapse-item" href="manageBuilding.php">Manage Buildings</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseSales"
            aria-expanded="true"
            aria-controls="collapseSales">
            <i class="fas fa-cash-register"></i>
            <span>Sale</span>
        </a>
        <div
            id="collapseSales"
            class="collapse"
            aria-labelledby="headingUser"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Sales Components :</h6>
                <a class="collapse-item" href="addSale.php">Add Sale</a>
                <a class="collapse-item" href="manageSale.php">Manage Sales</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="customer.php">
            <i class="fas fa-user-circle"></i>
            <span>Customer</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="payments.php">
            <i class="fas fa-credit-card"></i>
            <span>Payment</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->
    <div class="sidebar-heading">Business Expenses</div>
    <li class="nav-item">
        <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseExpenses"
            aria-expanded="true"
            aria-controls="collapseExpenses">
            <i class="fas fa-book"></i>
            <span>Expense</span>
        </a>
        <div
            id="collapseExpenses"
            class="collapse"
            aria-labelledby="headingUser"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Expense Components :</h6>
                <a class="collapse-item" href="addExpense.php">Add Expenses</a>
                <a class="collapse-item" href="expenseReport.php">Manage Expenses</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider" />
    <!-- Heading -->
    <div class="sidebar-heading">Business Reporting</div>
    <li class="nav-item">
        <a class="nav-link" href="report.php">
            <i class="fas fa-file-invoice"></i>
            <span>Report</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider" />
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
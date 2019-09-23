<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                    <div class="sidebar-brand-text mx-3"><span>Csell Admin</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="nav navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="/"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/profile.php"><i class="fas fa-user"></i><span>Profile</span></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/posts"><i class="fas fa-table"></i><span>Post</span></a></li>

                    <li class="nav-item" role="presentation"><a class="nav-link" href="/categories"><i class="fas fa-table"></i><span>Category</span></a></li>
                    <?php 
                        session_start();
if (isset($_SESSION['employee_type'])) {
    if ($_SESSION['employee_type'] ==  1) {
echo "<li class='nav-item' role='presentation'><a class='nav-link' href='/posts/pendingverify.php'><i class='fas fa-table'></i><span>Pending Posts</span></a></li>
<li class='nav-item' role='presentation'><a class='nav-link' href='/register.php'><i class='fas fa-table'></i><span>Register Employee</span></a></li>
<li class='nav-item' role='presentation'><a class='nav-link' href='/employee.php'><i class='fas fa-table'></i><span>Employee</span></a></li>";
    }
}                       
                     ?>

                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
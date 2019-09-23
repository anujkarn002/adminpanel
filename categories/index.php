<?php
session_start();
if (isset($_SESSION['employee_type'])) {
    if ($_SESSION['employee_type'] !=  1) {
        header('Location: /index.php');
    }
} else {
    header('Location: /login.php');
}

$connectstr_dbhost = '';
$connectstr_dbname = '';
$connectstr_dbusername = '';
$connectstr_dbpassword = '';

foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
        continue;
    }

    $connectstr_dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $connectstr_dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $connectstr_dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $connectstr_dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}




// Change this to your connection info.
$DATABASE_HOST = $connectstr_dbhost;
$DATABASE_USER = $connectstr_dbusername;
$DATABASE_PASS = $connectstr_dbpassword;
$DATABASE_NAME = 'indilabz_csell';


// Create connection
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <?php include '../includes/head-tag-contents.php'; ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include '../includes/navbar.php' ?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include '../includes/topbar.php' ?>

                <div class="container-fluid">
                    <h3 class="text-dark mb-4">Categories</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <div class="text-md-left"></div>
                            <p class="text-primary m-0 font-weight-bold">Category Info

                            </p>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-nowrap">
                                    <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label>Show&nbsp;<select class="form-control form-control-sm custom-select custom-select-sm">
                                                <option value="10" selected="">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>&nbsp;</label></div>

                                </div>

                                <div class="col-md-4">
                                    <div class=" dataTables_filter" id="dataTable_filter"><label><input type="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search"></label></div>

                                </div>
                                <div class="col-md-4 text-nowrap">
                                    <div class="text-md-right">

                                        <a href="create.php" class="btn btn-default btn-md">
                                            Add <span><i class="fas fa-plus"></i></span>
                                        </a>

                                        
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table dataTable my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                           <th>No.</th>
                                            <th>Category Image</th>
                                            <th>Category Title</th>
                                            <th>Category Updated At</th>
                                            <th>Category Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // read the product categories from the database
                                        if (mysqli_num_rows($result) > 0) {
                                            // output data of each row
                                            $no = 0;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                extract($row);
                                                $no++;
                                                echo "<tr>
                                                <td>{$no}</td>
                                                <td><img class='border rounded-circle img-profile' width=50 height=50 src='{$category_image}'></td>
                                                <td>{$category_title}</td>
                                                <td>{$updated_at}</td>
                                                <td>{$created_at}</td>
                                                <td><a href='edit.php?id={$category_id}' class='btn btn-sm btn-default'>Edit</a><a href='delete.php?id={$category_id}' class='btn btn-sm btn-default text-danger'>Delete</a></td>
                                            </tr>  ";
                                            }
                                        } else {
                                            echo "0 posts found";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><strong>No.</strong></td>
                                            <td><strong>Category Image</strong></td>
                                            <td><strong>Category Title</strong></td>
                                            <td><strong>Category Updated At</strong></td>
                                            <td><strong>Category Created At</strong></td>
                                            <td><strong>Action</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../includes/footer.php'; ?>
        </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="../assets/js/bs-charts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>
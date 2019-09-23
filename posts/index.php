<?php
session_start();

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

$sql = "SELECT posts.*, posts_meta_data.media_path, categories.category_title FROM posts INNER JOIN posts_meta_data ON posts.post_id=posts_meta_data.post_id INNER JOIN categories ON posts.category_id=categories.category_id WHERE verified=1";
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
                    <h3 class="text-dark mb-4">Posts</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <div class="text-md-left"></div>
                            <p class="text-primary m-0 font-weight-bold">Posts Info

                            </p>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-nowrap">
                                    <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                        <label>Show&nbsp;<select class="form-control form-control-sm custom-select custom-select-sm">
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

                                        <a href="/posts/create.php" class="btn btn-default btn-md">
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
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Currency Code</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Labels</th>
                                            <th>Tags</th>
                                            <th>Status</th>
                                            <th>User</th>
                                            <th>Post Updated At</th>
                                            <th>Category Created At</th>
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
                                                echo "
                                                <tr>
                                                <td>{$no}</td>
                                                <td><img src='{$media_path}' alt='no image' class='border rounded-circle img-profile' width=50 height=50></td>
                                                <td>{$title}</td>
                                                <td>{$description}</td>
                                                <td>{$currency_code}</td>
                                                <td>{$price}</td>
                                                <td>{$category_title}</td>
                                                <td>no label</td>
                                                <td>{$tags}</td>
                                                <td>{$post_status}</td>
                                                <td>{$user_id}</td>
                                                <td>{$updated_at}</td>
                                                <td>{$created_at}</td>
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
                                            <td><strong>Image</strong></td>
                                            <td><strong>Title</strong></td>
                                            <td><strong>Description</strong></td>
                                            <td><strong>Currency Code</strong></td>
                                            <td><strong>Price</strong></td>
                                            <td><strong>Category</strong></td>
                                            <td><strong>Labels</strong></td>
                                            <td><strong>Tags</strong></td>
                                            <td><strong>Status</strong></td>
                                            <td><strong>User</strong></td>
                                            <td><strong>Post Updated At</strong></td>
                                            <td><strong>Post Created At</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of 27</p>
                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
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
    <script src="assets/js/bs-charts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>
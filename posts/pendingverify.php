<?php
session_start();
if (isset($_SESSION['employee_type'])) {
    if ($_SESSION['employee_type'] !=  1) {
        header('Location: /');
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

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$no_of_records_per_page = 10;
$offset = ($pageno-1) * $no_of_records_per_page; 
$total_pages_sql = "SELECT COUNT(*) FROM posts";
$result = mysqli_query($conn,$total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

$sql = "SELECT posts.*, posts_meta_data.media_path, categories.category_title, categories.category_id, users.user_name FROM posts INNER JOIN posts_meta_data ON posts.post_id=posts_meta_data.post_id INNER JOIN users ON posts.user_id=users.user_id INNER JOIN categories ON posts.category_id=categories.category_id WHERE verified !=1 LIMIT $offset, $no_of_records_per_page";
$result = mysqli_query($conn, $sql);

if (isset($_GET['id'], $_GET['category'])) {
    $sqll = "UPDATE posts SET verified=1, category_id={$_GET['category']} WHERE post_id={$_GET['id']}";

    if (mysqli_query($conn, $sqll)) {
        header('Location: /posts/pendingverify.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // mysqli_close($conn);
}
if (isset($_GET['action'])) {
    $sqlll = "SELECT c.category_id, c.category_title FROM categories c";
    
    if ($categories = mysqli_query($conn, $sqlll)) {
        if (mysqli_num_rows($categories) > 0) {
            $c = array();
            while ($row = mysqli_fetch_assoc($categories)) {
            $c["{$row['category_title']}"] = $row['category_id'];
                }
            echo json_encode($c);
            }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // mysqli_close($conn);
}

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
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#post_create_modal">
                                            Add <span><i class="fas fa-plus"></i></span>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="post_create_modal" tabindex="-1" role="dialog" aria-labelledby="post_create_modalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Add New Post</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <h3 id="from_server" class="text-md-left text-success"></h3>
                                                        </div>
                                                        <form method="post" id="create_post">

                                                            <table class='table table-hover table-responsive table-bordered'>

                                                                <tr>
                                                                    <td>Title</td>
                                                                    <td><input type='text' name='post_title' id='post_title' class='form-control' /></td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Price</td>
                                                                    <td><input type='number' name='post_price' id='post_price' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Currency Code</td>
                                                                    <td><input type='text' name='post_currency_code' id='post_currency_code' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Description</td>
                                                                    <td><textarea name="post_description" id="post_description" cols="35" wrap="soft"></textarea></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Category</td>
                                                                    <td><input type='text' name='post_category_id' id='post_category_id' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Labels</td>
                                                                    <td><input type='text' name='post_labels' id='post_labels' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Tags</td>
                                                                    <td><input type='text' name='post_tags' id='post_tags' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Status</td>
                                                                    <td><input type='text' name='post_status' id='post_status' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>User</td>
                                                                    <td><input type='number' name='post_user_id' id='post_user_id' class='form-control' /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td>
                                                                        <button type="button" id="submit_create_post" class="btn btn-primary" disabled>Submit</button>
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </form>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php  ?>
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
                                                echo "
                                                <tr>
                                                <td>{$no}</td>
                                                <td><img src='{$media_path}' alt='no image' class='border rounded-circle img-profile' width=100 height=100></td>
                                                <td>{$title}</td>
                                                <td>{$description}</td>
                                                <td>{$currency_code}</td>
                                                <td>{$price}</td>
                                                <td><div id={$post_id}>
                                                <select class='form-control form-control-sm custom-select custom-select-sm' name='category'>
                                                <option value='{$category_id}' selected>{$category_title}</option>
                                                <div>
                                                </td>
                                                <td>no label</td>
                                                <td>{$tags}</td>
                                                <td>{$post_status}</td>
                                                <td>{$user_name}</td>
                                                <td>{$updated_at}</td>
                                                <td>{$created_at}</td>
                                                <td><button name='verify' id={$post_id} class='btn btn-success btn-sm btn-default'>Accept</button>
                                                <a href='/posts/delete.php?id={$post_id}' class='btn btn-danger btn-sm btn-default'>Reject</a></td>
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
                                            <td><strong>Action</strong></td>
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
                                        <!--<ul class="pagination">
                                            <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                        </ul>-->
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="?pageno=1"> First </a></li>
                                            <li class="page-item<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                                                <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>"> Prev </a>
                                            </li>
                                            <li class="page-item<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                                <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>"> Next </a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>"> Last</a></li>
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
    <script src="/assets/js/bs-charts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="/assets/js/theme.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() { 
                $.ajax({
                    type: "GET",
                    url: '/posts/getcategories.php',
                    data: {
                        action: 'get_category'
                    },
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);
                        $.each(result, function(i, value) {
                            $('select[name=category]').append($('<option>').text(i).attr('value', value));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(status);
                        console.log(error);
                        }
                    
                }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    });
            }, 500);
            $('button[name=verify]').on('click', function(e){
                var category_id = $('div[id='+e.target.id+']').find('select').val();
                $.ajax({
                    type: "GET",
                    url: window.location.href,
                    data: {
                        id: e.target.id,
                        category: category_id
                    },
                    success: function() {
                        location.reload(true);
                        console.log('successfully verified');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(status);
                        console.log(error);
                        }
                    
                }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    });
            });
            
        });
    </script>
</body>

</html>
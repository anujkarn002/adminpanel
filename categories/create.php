<?php
session_start();
if (isset($_SESSION['employee_type'])) {
    if ($_SESSION['employee_type'] !=  1) {
        header('Location: /index.php');
    }
}else{
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
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// We need to check if the account with that username exists.
if (isset($_POST['category_title'],$_POST['category_image'] )) {
    if ($stmt = $con->prepare('SELECT category_id FROM categories WHERE category_title = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
        $stmt->bind_param('s', $_POST['category_title']);
        $stmt->execute();
        $stmt->store_result();
        // Store the result so we can check if the category exists in the database.
        if ($stmt->num_rows > 0) {

            echo 'Category '.$_POST['category_title'].' exists, please choose another!';
        } else {
            // Category doesnt exists, insert new category
            if ($stmt = $con->prepare('INSERT INTO categories (category_title, category_image) VALUES (?, ?)')) {
                $stmt->bind_param('ss', $_POST['category_title'], $_POST['category_image']);
                $stmt->execute();
                echo 'You have successfully created category '.$_POST['category_title'];
                header('Location: /categories/index.php');
            } else {

                echo 'Could not prepare statement 1!';
            }
        }
        $stmt->close();
    } else {
        // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
        echo 'Could not prepare statement! 0';
    }
    $con->close();
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
                

<div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Create a Category!</h4>
                            </div>
                            <form method="post">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" placeholder="Category Title" name="category_title"></div>
                                </div>
                                <div class="form-group"><input class="form-control form-control-user" type="text" placeholder="Please enter url for image" name="category_image"></div>
                                
                                <button class="btn btn-primary btn-block text-white btn-user" type="submit">Create Category</button>
                                <hr>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                
            </div>
        </div>
        <?php include '../includes/footer.php';?>
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



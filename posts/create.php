<?php
session_start();
if (isset($_SESSION['employee_type'])) {
    if ($_SESSION['employee_type'] !=  1) {
        header('Location: /');
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
$DATABASE_NAME = 'indilabz_tracking';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// We need to check if the account with that username exists.
if (isset($_POST['email'])) {
    if ($stmt = $con->prepare('SELECT id, employee_password FROM employee WHERE employee_email = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->store_result();
        // Store the result so we can check if the account exists in the database.
        if ($stmt->num_rows > 0) {
            // Username already exists
            echo 'Email exists, please choose another!';
        } else {
            // Username doesnt exists, insert new account
            if ($stmt = $con->prepare('INSERT INTO employee (employee_name, employee_password, employee_email, employee_phone, employee_type, status) VALUES (?, ?, ?, ?, ?, ?)')) {
                // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param('ssssss', $_POST['name'], $password, $_POST['email'], $_POST['phone'], $a='2', $b='active');
                $stmt->execute();
                echo 'You have successfully registered, you can now login!';
                header('Location: /');
            } else {
                // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                echo 'Could not prepare statement 1!';
            }
        }
        $stmt->close();
    } else {
        // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
        echo 'Could not prepare statement! 0';
    }
}
$con->close();

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
                    <h3 class="text-dark mb-4">Create Posts</h3>
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
                                    <h3>Under Construction</h3>
                                    <button type="button" id="submit_create_post" class="btn btn-primary" disabled>Submit</button>
                                </td>
                            </tr>

                        </table>
                    </form>

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
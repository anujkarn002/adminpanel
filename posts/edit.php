<?php 
session_start();
if (isset($_SESSION['employee_type'])) {
    if ($_SESSION['employee_type'] !=  1) {
        header('Location: /');
    }
} else {
    header('Location: /login.php');
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
                    <h3 class="text-dark mb-4">Edit Post</h3>
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
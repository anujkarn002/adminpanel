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

if(isset($_GET['id'])){
// sql to delete a record
$sql = "DELETE FROM categories WHERE category_id={$_GET['id']}";

if (mysqli_query($conn, $sql)) {
    echo "Category deleted successfully";
    header('Location: /categories/index.php');
} else {
    echo "Error deleting category: " . mysqli_error($conn);
}

mysqli_close($conn);
}

?>

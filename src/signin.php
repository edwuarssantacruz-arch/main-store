<?php
// Get database connection
include('../settings/database.php');

// Get payload from frontend
$email = $_POST['email'];
$passwd = MD5($_POST['pswd']);

$sql_check_email = "SELECT * FROM users WHERE email = '$email' ";
$res = pg_query($local_conn, $sql_check_email);
$total = pg_num_rows($res);

if ($total > 0) {
    header('refresh:0;url=home.php');

} else {
   echo "<script>alert('User email does not exist .try again !!!') </script>";
   header('refresh:0;url=signin.html');

}

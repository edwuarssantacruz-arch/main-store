<?php
/*
user this  enpoint to test request

http://localhost/main-store/src/update_user_status.php?user_ID=ANY-ID-NUMBER
*/

    include('../settings/database.php');

    //get data

$user_id = $_GET['userID'];
$user_status = $_GET['userStatus'];


if ($user_status == 'active') {

    $new_userStatus = 'false';
} else {
    $new_userStatus = 'true';
}



    // PREPARE QUERY
$sql_update_user = "UPDATE users SET status = $new_userStatus WHERE id = $user_id ";
$res = pg_query($local_conn, $sql_update_user);


//validacion
if ($res) {
    echo "<script>alert('User status has been updated successfully !!!') </script>";
    header('refresh:0;url=list_users.php');

}else {
    echo "<script>alert('Error: User status has not been updated  !!!') </script>";
    header('refresh:0;url=list_users.php');
}
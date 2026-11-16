<?php
/*
user this  enpoint to test request

http://localhost/main-store/src/delete_user.php?user_ID=ANY-ID-NUMBER
*/

    include('../settings/database.php');

    //get data


    $user_id = $_GET['userID'];

    // PREPARE QUERY
$sql_delete_user = "DELETE FROM users WHERE id = $user_id ";
$res = pg_query($local_conn, $sql_delete_user);


//validacion
if ($res) {
    echo "<script>alert('User has been deleted successfully !!!') </script>";
    header('refresh:0;url=list_users.php');

}else {
    echo "<script>alert('Error: User has not been deleted successfully !!!') </script>";
    header('refresh:0;url=list_users.php');
}

    




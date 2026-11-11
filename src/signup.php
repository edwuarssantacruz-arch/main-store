<?php
// Get database connection
include('../settings/database.php');
 
// Get payload from frontend
$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email = $_POST['email'];
$passwd = MD5($_POST['pswd']);

$sql_check_email= "SELECT * FROM users WHERE email = '$email' ";

$res = pg_query($local_conn, $sql_check_email);

$total = pg_num_rows($res);

if($total<1){


 
            // Prepare query
            $sql = "
                INSERT INTO
                users (firstname, lastname, ident_number, email, password)
                VALUES (
                    '$first_name',
                    '$last_name',
                    '$ident_number',
                    '$email',
                    '$passwd'
                );
            ";
            
            // Execute query
            $res = pg_query($local_conn, $sql);
            
            if ($res) {
            // echo "User has been created successfully !!!";

            echo "<script>alert('User has bee create successfully !!!') </script>";
            header('refresh:0;url=signin.html');



            } else {
                //echo "Error: User hasn't been created successfully !!!";
            }

} else {
           echo "<script>alert('User email alredy exists .try again !!!') </script>";
        header('refresh:0;url=signup.html');



}



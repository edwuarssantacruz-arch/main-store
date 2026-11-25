<?php
// Get database connection
/*
session_start ();

  if(!isset($_SESSION['user_id'])){

        header('refresh:0;url=signin.html');
      exit();   
  }
*/

include('../settings/database.php');
 
// Get payload from frontend
$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email = $_POST['email'];
$passwd = password_hash($_POST['pswd'], PASSWORD_DEFAULT);
$photo_url ='photos/photo_defaul.png';


$sql_check_email= "SELECT * FROM users WHERE email = '$email' ";

$res = pg_query($local_conn, $sql_check_email);
$resupabase = pg_query($supa_conn, $sql_check_email);

$total = pg_num_rows($res);
$supabase_total = pg_num_rows($resupabase);

if($total<1 && $supabase_total<1){


 
            // Prepare query
            $sql = "
                INSERT INTO
                users (firstname, lastname, ident_number, email, password, url_photo , mobile_phone)
                VALUES (
                    '$first_name',
                    '$last_name',
                    '$ident_number',
                    '$email',
                    '$passwd',
                    '$photo_url',
                    '$mobile_phone'

                );
            ";
            
            // Execute query
            $res = pg_query($local_conn, $sql);
            $resupabase = pg_query($supa_conn, $sql);
            
            if ($res && $resupabase) {
            // echo "User has been created successfully !!!";

            echo "<script>alert('User has bee create successfully !!!') </script>";
            header('refresh:0;url=signin.html');



            } 

} else {
           echo "<script>alert('User email alredy exists .try again !!!') </script>";
        header('refresh:0;url=signup.html');



}



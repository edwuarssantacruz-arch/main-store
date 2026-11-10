<?php
<<<<<<< HEAD

=======
// Get database connection
include ('../settings/database.php');
>>>>>>> ad3a60927d14b1592e0e4142d0e93167bf7840c3
//Get data from frontend

$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email = $_POST['gmail'];
$passwd = MD5($_POST["pswd"]);


<<<<<<< HEAD
echo  "<br>".  $first_name;
echo  "<br>".  $last_name;
echo  "<br>".  $ident_number;
echo  "<br>".  $mobile_phone;
echo  "<br>".  $email;
echo  "<br>".  $passwd;
=======


// prepare query
$sql = "
INSERT INTO
users(firstname, lastname, ident_number, email, password) 
VALUES (
    '$first_name',
    '$last_name',
    '$ident_number',
    '$email',
    '$passwd'
    );

";



// execute query

$res = pg_query($local_conn, $sql); 

if($res) {
echo "user has been created successfully !!!";
}else {
    echo "Error: user has't bee created successfully!!!";
}
>>>>>>> ad3a60927d14b1592e0e4142d0e93167bf7840c3



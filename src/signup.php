<?php
// Get database connection
include ('../settings/database.php');
//Get data from frontend

$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email = $_POST['gmail'];
$passwd = MD5($_POST["pswd"]);




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



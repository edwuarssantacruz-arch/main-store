<?php 
/*use this url to print  data  in browser
http://127.0.0.1/main-store/src/list_users.php

include('../settings/database.php');



*/
  session_start ();

  if(!isset($_SESSION['user_id'])){

        header('refresh:0;url=signin.html');
      exit();   
  }





include('../settings/database.php');

//Get data  from users table


$sql="

SELECT 
    id,
    firstname || ' ' || lastname AS fullname,
    email,
    mobile_phone,
    CASE 
        WHEN status = true THEN 'active' 
        ELSE 'inactive' 
     END AS status,
    url_photo 
FROM 
    users;


";
$res = pg_query($local_conn, $sql);




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main_store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
  <select>
       <option >list all users</option>
       <option >list active users</option>
       <option >list inactive users</option>
  </select>

    <table class="table table-dark table-striped">
        <tr>
          <th>photo</th> 
          <th>full name</th>
          <th>email</th>
          <th>mobile phone</th>
          <th> status</th>
          <th>options</th>
        </tr>

        <?php while($row = pg_fetch_assoc($res)) { ?>


          <!--here print data from  database -->
          
        <tr>
          <th>
            <img src = "<?= $row['url_photo'] ?>" width ="40" alt="alt photo profile">
          </th> 
          <th><?= $row['fullname'] ?></th>
          <th><?= $row['email'] ?></th>
          <th><?= $row['mobile_phone'] ?></th>
          <th><?= $row['status'] ?></th>
          <td >
                <a href="edit_user.php?userID=<?= $row['id'] ?>">
                  
                  <img src="icons/pencil.png" alt="Edit" width="20"> 
              </a>
                  

                <a href="#">
                  <img src="icons/search.png" alt="Edit" width="20">
              </a>
                  

                <a href="delete_user.php?userID=<?= $row['id'] ?>">
                  <img src="icons/delete.png" alt="Edit" width="20"> 
              
                </a>


                 <a href="update_user_status.php?userID=<?= $row['id'] ?>&userStatus=<?= $row['status'] ?>">
                  <?php

                  if ($row['status'] == 'active') {
                      echo "<img src='icons/disable.png' alt='inactive user' width='20'>";
                  } else {
                      echo "<img src='icons/enable.png' alt='active user' width='20'>";
                  }



                  ?>
              
                </a>



              </td>

            </tr>
            <?php } ?>
         
    <table>
      <a href="home.php">Back to home</a>
  </body>
</html>
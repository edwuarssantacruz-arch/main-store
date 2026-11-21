<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('refresh:0;url=signin.html');
    exit();
}

include('../settings/database.php');

// Get user id sent by the pencil icon in list_users.php
$user_id = $_GET['userID'] ?? '';

$sql = "
    SELECT id, firstname, lastname, ident_number, email, mobile_phone, url_photo
    FROM users
    WHERE id = $1
";
$res = pg_query_params($local_conn, $sql, [$user_id]);

// If the user does not exist, go back to the list
if (!$res || pg_num_rows($res) < 1) {
    echo "<script>alert('User not found !!!') </script>";
    header('refresh:0;url=list_users.php');
    exit();
}

$row = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Store - Edit user</title>
    <link rel="icon" type="image/png" href="icons/Main-Store-ICONO.png">
</head>
<body>
    <h3>Edit user form</h3>

    <!-- enctype is REQUIRED to upload files -->
    <form name="edit-user-form" action="update_user.php" method="post" enctype="multipart/form-data">

        <!-- id travels hidden so update_user.php knows which user to update -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

        <label>Current photo:</label><br>
        <img id="preview" src="<?= htmlspecialchars($row['url_photo']) ?>" width="100" alt="profile photo"><br><br>

        <label>Change photo (optional, jpg/png/webp/gif, max 2MB):</label><br>
        <input type="file" name="photo" id="photo" accept="image/png, image/jpeg, image/webp, image/gif"><br><br>

        <label>Firstname:</label><br>
        <input type="text" name="fname" value="<?= htmlspecialchars($row['firstname']) ?>" required><br><br>

        <label>Lastname:</label><br>
        <input type="text" name="lname" value="<?= htmlspecialchars($row['lastname']) ?>" required><br><br>

        <label>Identification number:</label><br>
        <input type="text" name="idnumber" value="<?= htmlspecialchars($row['ident_number']) ?>" required><br><br>

        <label>Mobile phone:</label><br>
        <input type="text" name="mphone" value="<?= htmlspecialchars($row['mobile_phone']) ?>" required><br><br>

        <label>E-mail:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required><br><br>

        <button>Update</button>
        <a href="list_users.php">Cancel</a>

    </form>

    <script>
        // Show a preview of the selected photo before saving
        document.getElementById('photo').addEventListener('change', function () {
            if (this.files && this.files[0]) {
                document.getElementById('preview').src = URL.createObjectURL(this.files[0]);
            }
        });
    </script>
</body>
</html>

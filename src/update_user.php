<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('refresh:0;url=signin.html');
    exit();
}

include('../settings/database.php');

// Get payload from edit_user.php
$id           = $_POST['id'];
$first_name   = $_POST['fname'];
$last_name    = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email        = $_POST['email'];

$back_to_edit = 'edit_user.php?userID=' . urlencode($id);

// Check the email is not used by ANOTHER user
$sql_check_email = "SELECT id FROM users WHERE email = $1 AND id <> $2";
$res = pg_query_params($local_conn, $sql_check_email, [$email, $id]);

if (pg_num_rows($res) > 0) {
    echo "<script>alert('Email already exists in another user. Try again !!!') </script>";
    header('refresh:0;url=' . $back_to_edit);
    exit();
}

// Get the current photo (to keep it or to delete it if a new one is uploaded)
$res = pg_query_params($local_conn, "SELECT url_photo FROM users WHERE id = $1", [$id]);
$current = pg_fetch_assoc($res);
$old_photo = $current ? $current['url_photo'] : 'photos/photo_defaul.png';
$new_photo = $old_photo;      // by default keep the same photo
$uploaded_now = false;

// ---------- PHOTO UPLOAD (optional) ----------
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {

    $file = $_FILES['photo'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error uploading the photo (code " . (int)$file['error'] . ") !!!') </script>";
        header('refresh:0;url=' . $back_to_edit);
        exit();
    }

    // Max 2MB
    if ($file['size'] > 2 * 1024 * 1024) {
        echo "<script>alert('The photo is too big. Max 2MB !!!') </script>";
        header('refresh:0;url=' . $back_to_edit);
        exit();
    }

    // Validate the REAL type of the file (not just the extension)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    if (!isset($allowed[$mime])) {
        echo "<script>alert('Invalid file. Only jpg, png, webp or gif !!!') </script>";
        header('refresh:0;url=' . $back_to_edit);
        exit();
    }

    // Folder src/photos (create it if it does not exist)
    $folder = __DIR__ . '/photos/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    // Unique name so photos never overwrite each other
    $file_name = 'user_' . (int)$id . '_' . time() . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], $folder . $file_name)) {
        echo "<script>alert('Could not save the photo on the server !!!') </script>";
        header('refresh:0;url=' . $back_to_edit);
        exit();
    }

    $new_photo = 'photos/' . $file_name;   // this is what goes to the database
    $uploaded_now = true;
}

// ---------- UPDATE USER ----------
$sql_update_user = "
    UPDATE users SET
        firstname    = $1,
        lastname     = $2,
        ident_number = $3,
        email        = $4,
        mobile_phone = $5,
        url_photo    = $6
    WHERE id = $7
";
$res = pg_query_params($local_conn, $sql_update_user, [
    $first_name,
    $last_name,
    $ident_number,
    $email,
    $mobile_phone,
    $new_photo,
    $id
]);

// Validation
if ($res) {

    // Delete the old photo (but never the default one)
    if ($uploaded_now && $old_photo !== 'photos/photo_defaul.png') {
        $old_path = __DIR__ . '/photos/' . basename($old_photo);
        if (is_file($old_path)) {
            unlink($old_path);
        }
    }

    echo "<script>alert('User has been updated successfully !!!') </script>";
    header('refresh:0;url=list_users.php');

} else {

    // The database failed: remove the photo we just saved
    if ($uploaded_now) {
        $new_path = __DIR__ . '/photos/' . basename($new_photo);
        if (is_file($new_path)) {
            unlink($new_path);
        }
    }

    echo "<script>alert('Error: User has not been updated !!!') </script>";
    header('refresh:0;url=' . $back_to_edit);
}
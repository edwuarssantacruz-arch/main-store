<?php
// Conexión a la base de datos
include('../settings/database.php');

session_start();

// Si ya hay sesión iniciada, ir al home
if (isset($_SESSION['user_id']) && isset($_SESSION['user_firstname'])) {
    header('Location: home.php');
    exit;
}

// POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.html');
    exit;
}

// Datos del formulario
$email       = trim($_POST['email'] ?? '');
$passwd_form = $_POST['pswd'] ?? '';

// Q1: verificar que el email existe y traer los datos del usuario
$res = pg_query_params(
    $local_conn,
    'SELECT id, firstname, lastname, email, url_photo, password
       FROM users
      WHERE email = $1',
    [$email]
);

$total = $res ? pg_num_rows($res) : 0;

// Q2: verificar que el password del formulario coincide con el de la base de datos
$passwd = false;
$row    = null;

if ($total == 1) {
    $row    = pg_fetch_assoc($res);
    $passwd = password_verify($passwd_form, $row['password']);
}

// Acceso
if ($total == 1 && $passwd) {
    session_regenerate_id(true);
    $_SESSION['user_id']        = $row['id'];
    $_SESSION['user_firstname'] = $row['firstname'];
    $_SESSION['user_lastname']  = $row['lastname'];
    header('Location: home.php');
    exit;
}

// Sin acceso
echo "<script>
        alert('Invalid email or password. Try again!');
        window.location.href = 'signin.html';
      </script>";
exit;
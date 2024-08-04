<?php
require '../classes/UsersClass.php';

session_start();

// if (isset($_GET['edit_user'])) {
//     $user = new Users();
// }

if (isset($_POST['password_edit_submit'])) {
    $user = new Users();
    $user->changePassword();
}

if  (isset($_POST['user_edit_submit'])) {
    $user = new Users();
    $user->updateUser();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require './classes/UsersClass.php';

    session_start();

    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    } else {
        if ($_SESSION['type'] != 'Librarian') {
            header('Location: /');
            exit;
        }
    }
    ?>
    <?php include('includes/styles.php'); ?>
    <title>Library System - Users</title>
</head>

<body>
<?php include('includes/navbar.php'); ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <h1>All Users</h1>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="user_table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Username</th>
                    <th scope="col">Uni ID</th>
                    <th scope="col">User Type</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $user_obj = new Users();
                $users = json_decode($user_obj->getAllUsers());

                foreach ($users as $user) {
                    ?>
                    <tr>
                        <td><?php echo $user->user_id; ?></td>
                        <td><?php echo $user->fname; ?> <?php echo $user->lname; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo $user->uname; ?></td>
                        <td><?php echo $user->uni_id; ?></td>
                        <td><?php echo $user->type; ?></td>
                        <td>
                            <a target="_blank" href="/user_edit.php?user_id=<?php echo $user->user_id; ?>" class="btn btn-primary">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/javascript.php'); ?>
<script>
    $(document).ready(function () {
        $('#user_table').DataTable();
    });
</script>
</body>

</html>

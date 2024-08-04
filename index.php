<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == '1') {
        header('Location: /home.php');
        exit;
    }
    ?>
    <?php include('includes/styles.php'); ?>
    <title>Library System - Login</title>
</head>

<body>
    <?php include('includes/navbar.php'); ?>

    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center">Login</h3>
                            </div>
                        </div>

                        <form action="/actions/auth_actoins.php" method="post" id="login_form">
                            <?php
                            if (isset($_SESSION['error'])) {
                            ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <?php echo $_SESSION['error']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php

                                unset($_SESSION['error']);
                            }
                            ?>

                            <?php
                            if (isset($_SESSION['success'])) {
                            ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-primary">
                                            <?php echo $_SESSION['success']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php

                                unset($_SESSION['success']);
                            }
                            ?>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="un">Enter Username:</label>
                                    <input class="form-control" type="text" id="un" name="un" placeholder="john" required>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="pw">Enter Password:</label>
                                    <input class="form-control" type="password" id="pw" name="pw" placeholder="**********" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <input class="btn btn-primary form-control" type="submit" id="login_submit" name="login_submit" value="Login">
                                </div>
                            </div>

                            <div class="row mt-4 mb-2">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        Don't have an account?
                                        <a href="/sign_up.php">Sign Up</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/javascript.php'); ?>
    <script>
        function validateForm() {
            $('#login_form').validate({
                rules: {
                    un: {
                        required: true
                    },
                    pw: {
                        required: true
                    }
                },
                messages: {
                    un: {
                        required: "Username is required"
                    },
                    pw: {
                        required: "Password is required"
                    }
                },
            });
        }

        $(document).ready(function() {
            validateForm();
        });
    </script>
</body>

</html>
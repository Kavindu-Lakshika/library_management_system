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
    <title>Library System - Sign Up</title>
</head>

<body>
    <?php include('includes/navbar.php'); ?>

    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center">Sign Up</h3>
                            </div>
                        </div>

                        <?php
                        if (isset($_SESSION['errors'])) {
                            $errors = $_SESSION['errors'];

                            foreach ($errors as $error) {
                        ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <?php echo $error; ?>
                                        </div>
                                    </div>
                                </div>
                        <?php

                                unset($_SESSION['errors']);
                            }
                        }
                        ?>

                        <form action="/actions/auth_actoins.php" method="post" id="reg_form">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="fname">Enter First Name:</label>
                                    <input class="form-control" type="text" id="fname" name="fname" placeholder="John" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname">Enter Last Name:</label>
                                    <input class="form-control" type="text" id="lname" name="lname" placeholder="Doe" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="un">Enter Username:</label>
                                    <input class="form-control" type="text" id="un" name="un" placeholder="john" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Enter Email Address:</label>
                                    <input class="form-control" type="email" id="email" name="email" placeholder="john@domain.com" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="pw">Enter Password:</label>
                                    <input class="form-control" type="password" id="pw" name="pw" placeholder="**********" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cpw">Re-enter Password:</label>
                                    <input class="form-control" type="password" id="cpw" name="cpw" placeholder="**********" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="uid">Enter University provided ID Number:</label>
                                    <input class="form-control" type="text" id="uid" name="uid" placeholder="STU-XXXX or PRO-XXXX or LIB-XXXX" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <input class="btn btn-primary form-control" type="submit" id="reg_submit" name="reg_submit" value="Sign Up">
                                </div>
                            </div>

                            <div class="row mt-4 mb-2">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        Already have an account?
                                        <a href="/">Login</a>
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
            $('#reg_form').validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    lname: {
                        required: true,
                    },
                    un: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    pw: {
                        required: true,
                        passwordCheck: true
                    },
                    cpw: {
                        required: true,
                        equalTo: "#pw"
                    },
                    uid: {
                        required: true,
                        checkId: true
                    }
                },
                messages: {
                    fname: {
                        required: "First name is required.",
                    },
                    lname: {
                        required: "Last name is required.",
                    },
                    un: {
                        required: "Username is required.",
                    },
                    email: {
                        required: "Email is required.",
                        email: "Please enter a valid email address."
                    },
                    pw: {
                        required: "Password is required.",
                    },
                    cpw: {
                        required: "Re-entering is password required.",
                        equalTo: "Your passwords do not match."
                    },
                    uid: {
                        required: "University issued ID number is required."
                    }
                },
            });
        }

        $(document).ready(function() {
            $.validator.addMethod("passwordCheck", function(value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/.test(value);
            }, "Please enter a valid password (minimum 8 characters, at least 1 uppercase letter, 1 lowercase letter, and 1 number)");

            $.validator.addMethod("checkId", function(value, element) {
                let exists = false;

                if (value.startsWith('STU-') || value.startsWith('PRO-') || value.startsWith('LIB-')) {
                    exists = true;
                }

                return exists;
            }, "Enter a valid ID number which contains one of the prefixes 'STU-', 'PRO-' or 'LIB-' to ditermine the user group. (Example: 'STU-1234')");

            validateForm();
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require './classes/UsersClass.php';

    session_start();
    $user = array();
    $types = array();
    $user_id = "";

    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    } else {
        if (isset($_GET['user_id'])) {
            if ($_SESSION['type'] == 'Librarian') {
                $user_id = $_GET['user_id'];
            } else {
                $user_id = $_SESSION['user_id'];
            }
        } else {
            $user_id = $_SESSION['user_id'];
        }

        $users = new Users();
        $user = $users->getSingleUser($user_id);
        $types = $users->getUserTypes();
    }
    ?>
    <?php include('includes/styles.php'); ?>
    <title>Library System - Edit User</title>
</head>

<body>
<?php include('includes/navbar.php'); ?>

<div class="container">
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

    <?php
    if (isset($_SESSION['success'])) {
        ?>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; ?>
                </div>
            </div>
        </div>
        <?php

        unset($_SESSION['success']);
    }
    ?>

    <?php
    if (isset($_SESSION['pass_error'])) {
        ?>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <?php echo $_SESSION['pass_error']; ?>
                </div>
            </div>
        </div>
        <?php

        unset($_SESSION['pass_error']);
    }
    ?>

    <?php
    if (isset($_SESSION['pass_success'])) {
        ?>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <?php echo $_SESSION['pass_success']; ?>
                </div>
            </div>
        </div>
        <?php

        unset($_SESSION['pass_success']);
    }
    ?>

    <div class="row mt-5">
        <div class="col-md-6 border-right" style="border-right: 1px solid grey;">
            <div class="row">
                <div class="col-md-12">
                    <h3>Edit User Details</h3>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <form action="/actions/user_actions.php" method="post" id="user_edit_form">
                        <input type="hidden" name="id" value="<?php echo $user[0]["user_id"]; ?>">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="fname">Enter First Name:</label>
                                <input class="form-control" type="text" id="fname" name="fname" placeholder="John"
                                       value="<?php echo $user[0]["fname"]; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lname">Enter Last Name:</label>
                                <input class="form-control" type="text" id="lname" name="lname" placeholder="Doe"
                                       value="<?php echo $user[0]["lname"]; ?>" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="un">Enter Username:</label>
                                <input class="form-control" type="text" id="un" name="un" placeholder="john"
                                       value="<?php echo $user[0]["uname"]; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Enter Email Address:</label>
                                <input class="form-control" type="email" id="email" name="email"
                                       placeholder="john@domain.com" value="<?php echo $user[0]["email"]; ?>" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="uid">Enter University provided ID Number:</label>
                                <input class="form-control" type="text" id="uid" name="uid"
                                       placeholder="STU-XXXX or PRO-XXXX or LIB-XXXX"
                                       value="<?php echo $user[0]["uni_id"]; ?>" required>
                            </div>
                        </div>

                        <?php
                        if ($_SESSION['type'] == 'Librarian') {
                            ?>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="type">User Type:</label>
                                    <select class="form-control" name="type" id="type">
                                        <?php
                                        foreach ($types as $type) {
                                            $type_id = $type["user_type_id"];
                                            $user_type = $type["type"];

                                            $selected = "";

                                            if ($user_type == $_SESSION['type']) {
                                                $selected = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $type_id; ?>" <?php echo $selected; ?>><?php echo $user_type; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <input class="btn btn-primary form-control" type="submit" id="user_edit_submit"
                                       name="user_edit_submit" value="Save">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h3>Change Password</h3>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <form action="/actions/user_actions.php" method="post" id="password_edit_form">
                        <input type="hidden" name="id" value="<?php echo $user[0]["user_id"]; ?>">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="pw">Enter Password:</label>
                                <input class="form-control" type="password" id="pw" name="pw" placeholder="**********"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="cpw">Re-enter Password:</label>
                                <input class="form-control" type="password" id="cpw" name="cpw" placeholder="**********"
                                       required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <input class="btn btn-primary form-control" type="submit" id="password_edit_submit"
                                       name="password_edit_submit" value="Save">
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
    function validatePasswordForm() {
        $('#password_edit_form').validate({
            rules: {
                pw: {
                    required: true,
                    passwordCheck: true
                },
                cpw: {
                    required: true,
                    equalTo: "#pw"
                }
            },
            messages: {
                pw: {
                    required: "Password is required.",
                },
                cpw: {
                    required: "Re-entering is password required.",
                    equalTo: "Your passwords do not match."
                }
            },
        });
    }

    function validateDetailsForm() {
        $('#user_edit_form').validate({
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

    $(document).ready(function () {
        $.validator.addMethod("passwordCheck", function (value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/.test(value);
        }, "Please enter a valid password (minimum 8 characters, at least 1 uppercase letter, 1 lowercase letter, and 1 number)");

        $.validator.addMethod("checkId", function (value, element) {
            let exists = false;

            if (value.startsWith('STU-') || value.startsWith('PRO-') || value.startsWith('LIB-')) {
                exists = true;
            }

            return exists;
        }, "Enter a valid ID number which contains one of the prefixes 'STU-', 'PRO-' or 'LIB-'. (Example: 'STU-1234')");

        validateDetailsForm();
        validatePasswordForm();
    });
</script>
</body>

</html>
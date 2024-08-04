<?php
require 'Database.php';

class Auth
{
    public function loginAction()
    {
        // Create a new database object
        $db = new Database();

        // Retrieve the username and password from the POST request
        $un = $_POST['un'];
        $pw = md5($_POST['pw']);

        // Construct a query to select a user with the matching username and password
        $query = "SELECT * FROM users INNER JOIN user_type ON users.user_type_id = user_type.user_type_id WHERE uname='$un' AND pw='$pw'";

        // Execute the query and retrieve the results
        $result = $db->query($query);
        $users = $db->fetchAll($result);

        // If there are any matching users, set session variables and redirect to the home page
        if (count($users) > 0) {
            $_SESSION['logged_in']  = '1';
            $_SESSION['user_id']  = $users[0]['user_id'];
            $_SESSION['name']  = $users[0]['fname'] . ' ' . $users[0]['lname'];
            $_SESSION['type']  = $users[0]['type'];
            header('Location: /home.php');
        } else { // If there are no matching users, set an error message in the session and redirect back to the login page
            $_SESSION['error'] = "Incorrect username or password";
            header('Location: /');
        }
        exit;
    }

    public function registerAction()
    {
        // Create a new database object
        $db = new Database();

        // Retrieve form data
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $un = $_POST['un'];
        $pw = md5($_POST['pw']); // Encrypt the password
        $uid = $_POST['uid'];
        $type_id = self::getUsertypeId($uid, $db); // Get the user type ID

        // Check if the username already exists
        $query_un = "SELECT * FROM users WHERE uname='$un'";
        $result_un = $db->query($query_un);
        $users_un = $db->fetchAll($result_un);

        // Check if the email address already exists
        $query_em = "SELECT * FROM users WHERE email='$email'";
        $result_em = $db->query($query_em);
        $users_em = $db->fetchAll($result_em);

        // Check if the university ID already exists
        $query_id = "SELECT * FROM users WHERE uni_id='$uid'";
        $result_id = $db->query($query_id);
        $users_id = $db->fetchAll($result_id);

        // Flag to check if the user already exists
        $user_exists = false;
        // Array to store error messages
        $error_msgs = array();

        // Check if the username already exists
        if (count($users_un) > 0) {
            $user_exists = true;
            array_push($error_msgs, "Username already exists");
        }

        // Check if the email address already exists
        if (count($users_em) > 0) {
            $user_exists = true;
            array_push($error_msgs, "Email address already exists");
        }

        // Check if the university ID already exists
        if (count($users_id) > 0) {
            $user_exists = true;
            array_push($error_msgs, "University Id already exists");
        }

        // If the user does not already exist, insert the new user into the database
        if (!$user_exists) {
            $query = "INSERT INTO users (fname, lname, email, uname, pw, uni_id, user_type_id) VALUES('$fname', '$lname', '$email', '$un', '$pw', '$uid', '$type_id')";
            $result = $db->query($query);

            // If the insert was successful, redirect the user to the login page
            if ($result == '1') {
                $_SESSION['success'] = "You have signed up successfully. Please login.";
                header('Location: /');
            } else {
                // If there was an error, add an error message to the array and redirect the user to the sign up page
                $_SESSION['errors'] = $error_msgs;
                header('Location: /sign_up.php');
            }
            exit;
        }
    }

    public function getUsertypeId($uid, $db)
    {
        // explode the $uid by the hyphen character to get the type identifier
        $type_exploded = explode('-', $uid);
        $type = "";

        // determine the type based on the identifier
        if ($type_exploded[0] === "STU") {
            $type = "Student";
        } else if ($type_exploded[0] === "PRO") {
            $type = "Professor";
        } else if ($type_exploded[0] === "LIB") {
            $type = "Librarian";
        }

        // get the user_type_id from the user_type table based on the type
        $query = "SELECT user_type_id FROM user_type WHERE type='$type'";
        $result = $db->query($query);
        $type_ids = $db->fetchAll($result);

        // return the user_type_id
        return $type_ids[0]["user_type_id"];
    }

    public function logoutAction($confirmation)
    {
        // If the user has confirmed their intention to log out
        if ($confirmation == 'true') {
            // Destroy the current session
            session_destroy();
            // Redirect the user to the homepage
            header('Location: /');
        } else {
            // If the user has not confirmed their intention to log out, redirect them back to the home page
            header('Location: /home.php');
        }
        exit;
    }
}

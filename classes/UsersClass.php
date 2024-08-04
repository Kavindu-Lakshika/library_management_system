<?php
require 'Database.php';

class Users
{
    public function getSingleUser($user_id)
    {
        // create a new Database object
        $db = new Database();

        // select all data from the users and user_type tables, joining the user_type table on the user_type_id column
        // where the user_id matches the provided $user_id parameter
        $query = "SELECT * FROM users INNER JOIN user_type ON users.user_type_id = user_type.user_type_id WHERE user_id='$user_id'";

        // execute the query
        $result = $db->query($query);

        // fetch all the rows from the result
        $users = $db->fetchAll($result);

        // return the fetched rows
        return $users;
    }

    public function getUserTypes()
    {
        // create a new Database object
        $db = new Database();

        // select all data from the user_type table
        $query = "SELECT * FROM user_type";

        // execute the query
        $result = $db->query($query);

        // fetch all the rows from the result
        $types = $db->fetchAll($result);

        // return the fetched rows
        return $types;
    }

    public function updateUser()
    {
        // Start a new session
        session_start();

        // Initialize an array to store error messages
        $errors = array();

        // Get the form data from the POST request
        $id = $_POST['id'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $uname = $_POST['un'];
        $uni_id = $_POST['uid'];

        // Create a new Database object
        $db = new Database();

        // First, check if the email, uid, and uname are unique for the user being updated
        $check_query = "SELECT * FROM users WHERE (email = '$email' OR uni_id = '$uni_id' OR uname = '$uname') AND user_id != '$id'";
        $check_result = $db->query($check_query);
        $check_rows = $db->fetchAll($check_result);
        if (count($check_rows) > 0) {
            // If the email, uid, or uname is not unique, add an error message to the errors array
            $errors[] = "Error: Email, UID, or username is already in use by another user.";
        } else {
            // If the email, uid, and uname are unique, update the user
            $query = "UPDATE users SET fname = '$fname', lname = '$lname', email = '$email', uname = '$uname', uni_id = '$uni_id'";
            if (isset($_POST['type'])) {
                // Only update the user_type_id field if it is included in the POST request
                $user_type_id = $_POST['type'];
                $query .= ", user_type_id = '$user_type_id'";
            }
            $query .= " WHERE user_id = '$id'";
            $result = $db->query($query);
            if ($result) {
                // If the update was successful, assign a success message to the success variable
                $success = "User updated successfully.";
            } else {
                // If the update failed, add an error message to the errors array
                $errors[] = "Error updating user: " . $db->conn->error;
            }
        }

        // Add the errors array to the session if it is not empty
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        }
        // Add the success message to the session if it is set
        if (isset($success)) {
            $_SESSION['success'] = $success;
        }

        // Redirect to the page where the request came from
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function changePassword()
    {
        // get the user_id and password from the POST data
        $user_id = $_POST['id'];
        $pw = md5($_POST['pw']);

        // create a new Database object
        $db = new Database();

        // update the users table, setting the pw column to the provided password where the user_id matches the provided $user_id parameter
        $query = "UPDATE users SET pw='$pw' WHERE user_id='$user_id'";

        // execute the query
        $result = $db->query($query);

        //  if the query was successful (result equals 1), set a session variable and redirect back to the previous page
        if ($result) {
            $_SESSION['pass_success'] = "Password changed successfully.";
        } else { // if the query was not successful, set a session variable and redirect back to the previous page
            $_SESSION['pass_error'] = "Error changing password: " . $db->conn->error;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function getAllUsers()
    {
        // Create a new Database object
        $db = new Database();

        // Create a SQL query to select all users from the 'users' table
        $query = "SELECT * FROM users INNER JOIN user_type ut on users.user_type_id = ut.user_type_id";

        // Execute the query and store the result in a variable
        $results = $db->query($query);

        // Fetch all rows of data from the query result
        $users = $db->fetchAll($results);

        // Return the data as a JSON encoded string
        return json_encode($users);
    }

}

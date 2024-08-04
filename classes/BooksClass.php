<?php
require 'Database.php';

class Books
{
    public function addBook()
    {
        $isbn = $_POST['isbn'];
        $name = $_POST['name'];
        $image = $_FILES['image'];
        $author = $_POST['author'];
        $category = $_POST['category'];
        $desc = $_POST['desc'];

        // Create a new instance of the Database class
        $db = new Database();

        // Check if the ISBN already exists in the books table
        $isbnResult = $db->query("SELECT isbn FROM books WHERE isbn='$isbn'");
        $isbnRow = $db->fetchAll($isbnResult);
        if (count($isbnRow) > 0) {
            // If the ISBN already exists, set an error message in the session and redirect to the page where the request came from
            $_SESSION['error'] = "ISBN already exists.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $cat_id = $this->getCategoryId($db, $category);
        $author_id = $this->getAuthorId($db, $author);
        $upload_data = json_decode($this->uploadImage($isbn, $image));

        if ($upload_data->moved) {
            $imageName = $upload_data->name;

            // Insert the book into the books table
            $result = $db->query("INSERT INTO books (isbn, name, image, author_id, cat_id, `desc`) VALUES ('$isbn', '$name', '$imageName', $author_id, $cat_id, '$desc')");
            if ($result) {
                // If the book is successfully saved, set a success message in the session
                $_SESSION['success'] = "Book successfully saved!";
            } else {
                // If there is an error saving the book, set an error message in the session
                $_SESSION['error'] = "Error saving book. Please try again.";
            }
        } else {
            // If there is an error uploading the image, set an error message in the session
            $_SESSION['error'] = "Error uploading image. Please try again.";
        }

        // Redirect to the page where the request came from
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function getCategoryId($db, $category)
    {
        // Initialize the cat_id variable
        $cat_id = "";

        // Check if the category exists in the category table
        $catResult = $db->query("SELECT cat_id FROM category WHERE category='$category'");
        $catRow = $db->fetchAll($catResult);
        if (count($catRow) > 0) {
            // If the category exists, get the cat_id
            $cat_id = $catRow[0]['cat_id'];
        } else {
            // If the category does not exist, insert it into the table and get the cat_id
            $db->query("INSERT INTO category (category) VALUES ('$category')");
            $catResult = $db->query("SELECT cat_id FROM category WHERE category='$category'");
            $catRow = $db->fetchAll($catResult);
            $cat_id = $catRow[0]['cat_id'];
        }

        // Return the cat_id
        return $cat_id;
    }

    public function getAuthorId($db, $author)
    {
        // Initialize the author_id variable
        $author_id = "";

        // Check if the author exists in the authors table
        $authorResult = $db->query("SELECT author_id FROM authors WHERE name='$author'");
        $authorRow = $db->fetchAll($authorResult);
        if (count($authorRow) > 0) {
            // If the author exists, get the author_id
            $author_id = $authorRow[0]['author_id'];
        } else {
            // If the author does not exist, insert it into the table and get the author_id
            $db->query("INSERT INTO authors (name) VALUES ('$author')");
            $authorResult = $db->query("SELECT author_id FROM authors WHERE name='$author'");
            $authorRow = $db->fetchAll($authorResult);
            $author_id = $authorRow[0]['author_id'];
        }

        // Return the author_id
        return $author_id;
    }

    public function uploadImage($isbn, $image)
    {
        // Check if the book_covers folder exists
        if (!is_dir('../book_covers')) {
            // Create the book_covers folder if it doesn't exist
            mkdir('../book_covers');
        }

        // Rename the image with the ISBN and move it to the book_covers folder
        $imageName = $isbn . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
        $imagePath = '../book_covers/' . $imageName;
        // If the file already exists, delete it
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        // Move the uploaded image to the book_covers folder
        $files_moved = move_uploaded_file($image['tmp_name'], $imagePath);

        $data = array(
            "moved" => $files_moved,
            "name" => $imageName,
        );

        // Return the success of the file move and the image name as JSON data
        return json_encode($data);
    }

    public function updateBook()
    {
        $book_id = $_POST['book_id'];
        $isbn = $_POST['isbn'];
        $name = $_POST['name'];
        $image = $_FILES['image'];
        $author = $_POST['author'];
        $category = $_POST['category'];
        $desc = $_POST['desc'];

        // Create a new instance of the Database class
        $db = new Database();

        // Check if the ISBN already exists in the books table
        $isbnResult = $db->query("SELECT isbn FROM books WHERE isbn='$isbn' AND book_id!='$book_id'");
        $isbnRow = $db->fetchAll($isbnResult);
        if (count($isbnRow) > 0) {
            // If the ISBN already exists, set an error message in the session and redirect to the page where the request came from
            $_SESSION['error'] = "ISBN already exists.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $cat_id = $this->getCategoryId($db, $category);
        $author_id = $this->getAuthorId($db, $author);

        if (!($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE)) {
            $upload_data = json_decode($this->uploadImage($isbn, $image));

            if ($upload_data->moved) {
                $imageName = $upload_data->name;

                // Update the book
                $result = $db->query("UPDATE books SET isbn='$isbn', name='$name', image='$imageName', author_id=$author_id, cat_id=$cat_id, `desc`='$desc' WHERE book_id = '$book_id'");
                if ($result) {
                    // If the book is successfully saved, set a success message in the session
                    $_SESSION['success'] = "Book successfully updated!";
                } else {
                    // If there is an error saving the book, set an error message in the session
                    $_SESSION['error'] = "Error updating book. Please try again.";
                }
            } else {
                // If there is an error uploading the image, set an error message in the session
                $_SESSION['error'] = "Error uploading image. Please try again.";
            }
        } else {
            // Update the book without image
            $result = $db->query("UPDATE books SET isbn='$isbn', name='$name', author_id=$author_id, cat_id=$cat_id, `desc`='$desc' WHERE book_id = '$book_id'");
            if ($result) {
                // If the book is successfully saved, set a success message in the session
                $_SESSION['success'] = "Book successfully updated!";
            } else {
                // If there is an error saving the book, set an error message in the session
                $_SESSION['error'] = "Error updating book. Please try again.";
            }
        }

        // Redirect to the page where the request came from
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function getBookById()
    {
        // Get the book_id from the URL query string
        $book_id = $_GET['book_id'];

        // Connect to the database
        $db = new Database();

        // Get the book, author, and category data from the database
        $results = $db->query("SELECT books.*, a.name as author, c.category FROM books INNER JOIN authors a on books.author_id = a.author_id INNER JOIN category c on books.cat_id = c.cat_id WHERE book_id='$book_id'");

        // Fetch the data as an associative array
        $rows = $db->fetchAll($results);

        // Return the data as JSON
        return json_encode($rows[0]);
    }

    public function getReservationsById() {
        // Get the book_id from the URL query string
        $book_id = $_GET['book_id'];

        // Connect to the database
        $db = new Database();

        // Get the reservation data from the database
        $results = $db->query("SELECT reserve.*, u.fname, u.lname FROM reserve INNER JOIN users u on reserve.user_id = u.user_id WHERE book_id='$book_id'");

        // Fetch the data as an associative array
        $rows = $db->fetchAll($results);

        // Return the data as JSON
        return json_encode($rows);
    }

    public function reserveBook() {
        // Get data from form
        $date = $_POST['date'];
        $timestamp = strtotime($date);
        $formatted_date = date('Y-m-d', $timestamp); // Format date
        $user = $_POST['user_id'];
        $book = $_POST['book_id'];

        // Create a new instance of the Database class
        $db = new Database();

        // Insert reservation data
        $results = $db->query("INSERT INTO reserve(from_date, book_id, user_id) VALUES ('$formatted_date', '$book', '$user')");
        if ($results) {
            // If the reservation is successfully saved, set a success message in the session
            $_SESSION['success'] = "Book successfully reserved!";
        } else {
            // If there is an error reserving the book, set an error message in the session
            $_SESSION['error'] = "Error reserving book. Please try again.";
        }

        // Redirect to the page where the request came from
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function removeReservation() {
        // Get reservation id
        $res_id = $_GET['delete_res_id'];

        // Create a new instance of the Database class
        $db = new Database();

        // Delete reservation
        $results = $db->query("DELETE FROM reserve WHERE res_id='$res_id'");
        if ($results) {
            // If the reservation is successfully deleted, set a success message in the session
            $_SESSION['success'] = "Reservation successfully removed!";
        } else {
            // If there is an error deleting the reservation, set an error message in the session
            $_SESSION['error'] = "Error reserving book. Please try again.";
        }

        // Redirect to the page where the request came from
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function getCategories()
    {
        // Create a new database object
        $db = new Database();

        // Select all categories from the 'category' table
        $results = $db->query('SELECT category FROM category');

        // Fetch all rows from the query results
        $rows = $db->fetchAll($results);

        // Initialize an empty array to store the categories
        $categories = [];

        // Loop through each row and add the category to the array
        foreach ($rows as $row) {
            array_push($categories, $row["category"]);
        }

        // Return the categories array as a JSON encoded string
        return json_encode($categories);
    }

    public function getAuthors()
    {
        // Create a new database object
        $db = new Database();

        // Select all names from the 'authors' table
        $results = $db->query('SELECT name FROM authors');

        // Fetch all rows from the query results
        $rows = $db->fetchAll($results);

        // Initialize an empty array to store the authors
        $authors = [];

        // Loop through each row and add the author's name to the array
        foreach ($rows as $row) {
            array_push($authors, $row["name"]);
        }

        // Return the authors array as a JSON encoded string
        return json_encode($authors);
    }

    public function getBooks()
    {
        // Create a new database object
        $db = new Database();

        // Select all columns from the 'books' table and join with the 'authors' and 'category' tables using the 'author_id' and 'cat_id' columns, respectively
        $results = $db->query('SELECT books.*, a.name as author, c.category FROM books INNER JOIN authors a on books.author_id = a.author_id INNER JOIN category c on books.cat_id = c.cat_id');

        // Fetch all rows from the query results
        $rows = $db->fetchAll($results);

        // Return the rows array as a JSON encoded string
        return json_encode($rows);
    }
}
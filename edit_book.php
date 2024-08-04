<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require './classes/BooksClass.php';

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

    $books = new Books();
    $categories = $books->getCategories();
    $authors = $books->getAuthors();
    $book = json_decode($books->getBookById());
    ?>
    <?php include('includes/styles.php'); ?>
    <title>Library System - Edit Books</title>
</head>
<body>
<?php include('includes/navbar.php'); ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <form method="post" action="/actions/book_actions.php" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?php echo $book->book_id ?>">
                <div class="row mt-2 mb-5">
                    <div class="col-md-12">
                        <h1>Edit Book</h1>
                    </div>
                </div>

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

                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="isbn">ISBN:</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo $book->isbn ?>" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="name">Book Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $book->name ?>" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="image">Book Cover Image:</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="desc">Description:</label>
                            <textarea class="form-control" name="desc" id="desc" rows="5" required><?php echo $book->desc ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control typeahead" id="author" name="author" value="<?php echo $book->author ?>" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <input type="text" class="form-control typeahead" id="category" name="category" value="<?php echo $book->category ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-10"></div>
                    <div class="col">
                        <input type="submit" class="btn btn-primary form-control" name="edit_book_form"
                               id="edit_book_form" value="Update">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<textarea style="display: none;" id="cat_data" cols="30" rows="10"><?php print_r($categories); ?></textarea>
<textarea style="display: none;" id="auth_data" cols="30" rows="10"><?php print_r($authors); ?></textarea>

<?php include('includes/javascript.php'); ?>

<script>
    $(document).ready(function () {
        let authors = JSON.parse($('#auth_data').html());
        let categories = JSON.parse($('#cat_data').html());

        $('#author').typeahead({
            name: 'author',
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: function (query, processSync, processAsync) {
                let filteredAuthors = authors.filter(author => author.toLowerCase().includes(query.toLowerCase()));
                processSync(filteredAuthors);
            }
        });

        $('#category').typeahead({
            name: 'category',
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: function (query, processSync, processAsync) {
                let filteredCategories = categories.filter(category => category.toLowerCase().includes(query.toLowerCase()));
                processSync(filteredCategories);
            }
        });

        $('form').validate({
            rules: {
                isbn: {
                    required: true,
                    maxlength: 45
                },
                name: {
                    required: true,
                    maxlength: 45
                },
                image: {
                    accept: "image/*"
                },
                desc: {
                    required: true,
                    maxlength: 4000
                },
                author: {
                    required: true,
                    maxlength: 100
                },
                category: {
                    required: true,
                    maxlength: 45
                }
            },
            messages: {
                isbn: {
                    required: "Please enter an ISBN",
                    maxlength: "ISBN must be no more than 45 characters"
                },
                name: {
                    required: "Please enter a book name",
                    maxlength: "Book name must be no more than 45 characters"
                },
                image: {
                    accept: "Only image files are allowed"
                },
                desc: {
                    required: "Please enter a description",
                    maxlength: "Description must be no more than 4000 characters"
                },
                author: {
                    required: "Please enter an author name",
                    maxlength: "Author name must be no more than 100 characters"
                },
                category: {
                    required: "Please enter a category",
                    maxlength: "Category must be no more than 45 characters"
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });
</script>
</body>
</html>
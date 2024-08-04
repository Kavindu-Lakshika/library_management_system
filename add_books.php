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
    $books_data = json_decode($books->getBooks());
    ?>
    <?php include('includes/styles.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.min.js"
            integrity="sha512-59nEVFc+y/gLPL2WjOAQqCbvZbs49xWdTiCxVpjeKhb8oyMviN8fcwFRl8VxVzeLfiH/NKpklQqqLFg0NdZBaQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Library System - Add Books</title>
</head>

<body>
<?php include('includes/navbar.php'); ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <form method="post" action="/actions/book_actions.php" enctype="multipart/form-data">
                <div class="row mt-2 mb-5">
                    <div class="col-md-12">
                        <h1>Add a New Book</h1>
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
                            <input type="text" class="form-control" id="isbn" name="isbn" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="name">Book Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="image">Book Cover Image:</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                   required>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="desc">Description:</label>
                            <textarea class="form-control" name="desc" id="desc" rows="5" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control typeahead" id="author" name="author" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <input type="text" class="form-control typeahead" id="category" name="category" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-10"></div>
                    <div class="col">
                        <input type="submit" class="btn btn-primary form-control" name="add_book_form"
                               id="add_book_form" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <hr>
    </div>

    <div class="row mt-2 mb-5">
        <div class="col">
            <table id="book-table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col">Book ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">ISBN</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Category</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($books_data as $book) {
                    ?>
                    <tr>
                        <td><?php echo $book->book_id ?></td>
                        <td>
                            <img class="img-fluid" style="width: 100px;" src="../book_covers/<?php echo $book->image ?>"
                                 alt="<?php echo $book->image ?>">
                        </td>
                        <td><?php echo $book->isbn ?></td>
                        <td><?php echo $book->name ?></td>
                        <td><?php echo $book->author ?></td>
                        <td><?php echo $book->category ?></td>
                        <td>
                            <a target="_blank" href="/edit_book.php?book_id=<?php echo $book->book_id ?>"
                               class="btn btn-primary">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            <a target="_blank" href="/view_book.php?book_id=<?php echo $book->book_id ?>"
                               class="btn btn-success">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button id="<?php echo $book->book_id ?>" class="btn btn-danger btn_delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<textarea style="display: none;" id="cat_data" cols="30" rows="10"><?php print_r($categories); ?></textarea>
<textarea style="display: none;" id="auth_data" cols="30" rows="10"><?php print_r($authors); ?></textarea>

<?php include('includes/javascript.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.min.js"
        integrity="sha512-59nEVFc+y/gLPL2WjOAQqCbvZbs49xWdTiCxVpjeKhb8oyMviN8fcwFRl8VxVzeLfiH/NKpklQqqLFg0NdZBaQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).on('click', '.btn_delete', function (event) {
        let book_id = this.id;
        let url = '/edit_book.php?book_id=' + book_id;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    $(document).ready(function () {
        $('#book-table').DataTable();

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
                    required: true,
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
                    required: "Please select a book cover image",
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

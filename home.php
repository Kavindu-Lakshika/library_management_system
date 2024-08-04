<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require './classes/BooksClass.php';

    session_start();

    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    }

    $books = new Books();
    $categories = $books->getCategories();
    $books_data = $books->getBooks();
    ?>
    <?php include('includes/styles.php'); ?>
    <title>Library System - Home</title>
</head>

<body>
<?php include('includes/navbar.php'); ?>
<textarea style="display: none;" name="" id="cats" cols="30" rows="10"><?php echo $categories; ?></textarea>
<textarea style="display: none;" name="" id="books" cols="30" rows="10"><?php print_r($books_data); ?></textarea>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <?php
            date_default_timezone_set("GMT");
            $hour = date("G");
            $greet = "";

            if ($hour < 12) {
                $greet = "Good morning!";
            } elseif ($hour < 18) {
                $greet = "Good afternoon!";
            } else {
                $greet = "Good evening!";
            }
            ?>
            <h4><?php echo $greet; ?></h4>
            <h2><?php echo $_SESSION['name']; ?></h2>
        </div>
    </div>

    <hr>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="input-group mb-3">
                <span class="input-group-text">Filter by Category</span>
                <select name="cat" id="cat" class="form-control">
                    <option value="all">All</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3" id="books_display"></div>
</div>

<?php include('includes/javascript.php'); ?>

<script>
    let categories = JSON.parse($('#cats').html());
    let books = JSON.parse($('#books').html());

    function setCategories() {
        $.each(categories, function (index, value) {
            let html = '<option value="' + value + '">' + value + '</option>'
            $('#cat').append(html);
        });
    }

    function setBooks() {
        let html = '';

        $.each(books, function (index, element) {
            html += setHtml(element.image, element.name, element.author, element.isbn, element.book_id);
        });

        $('#books_display').html(html);
    }

    function filterBooks() {
        $('#cat').on('change', function () {
            let category = $('#cat').val();

            let display = $('#books_display');
            display.html('');
            let html = '';

            $.each(books, function (index, element) {
                if (category === element.category) {
                    html += setHtml(element.image, element.name, element.author, element.isbn, element.book_id);
                } else if (category === "all") {
                    html += setHtml(element.image, element.name, element.author, element.isbn, element.book_id);
                }
            });

            display.html(html);
        })
    }

    function setHtml(image, title, author, isbn, id) {
        return '<div class="col-md-3" style="margin-bottom: 30px;">'
            + '<div class="card" style="height: 640px;">'
            + '<div class="card-body" style="position: relative;">'
            + '<div class="row">'
            + '<div class="col-md-12">'
            + '<img src="../book_covers/' + image + '" alt="" style="width: 100%; height: auto;">'
            + '</div>'
            + '</div>'
            + '<div class="row mt-3 justify-content-center">'
            + '<div class="col-md-12 text-center">'
            + '<h4>' + title + '</h4>'
            + '<h6 class="text-muted">' + author + '</h6>'
            + '<h6 class="text-muted">' + isbn + '</h6>'
            + '</div>'
            + '</div>'
            + '<div class="row mt-3" style="position: absolute; width: 100%; bottom: 13px; left: 13px;">'
            + '<div class="col-md-12">'
            + '<a target="_blank" style="width: 100%;" href="/view_book.php?book_id=' + id + '" class="btn btn-primary">'
            + '<i class="fa fa-eye"></i> View'
            + '</a>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>';
    }

    $(document).ready(function () {
        setCategories();
        setBooks();
        filterBooks();
    });
</script>
</body>

</html>
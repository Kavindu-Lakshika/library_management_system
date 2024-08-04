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
    $authors = $books->getAuthors();
    $book = json_decode($books->getBookById());
    $reserves = $books->getReservationsById();
    ?>
    <?php include('includes/styles.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.min.js"
            integrity="sha512-59nEVFc+y/gLPL2WjOAQqCbvZbs49xWdTiCxVpjeKhb8oyMviN8fcwFRl8VxVzeLfiH/NKpklQqqLFg0NdZBaQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Library System - <?php echo $book->name; ?></title>
</head>
<body>
<?php include('includes/navbar.php'); ?>
<textarea style="display: none;" id="reserves" cols="30" rows="10"><?php echo $reserves; ?></textarea>
<input type="hidden" id="user_type" value="<?php echo $_SESSION['type']; ?>">
<div class="container mt-5">

    <?php
    if (isset($_SESSION['success'])) {
        ?>
        <div class="row mt-3 mb-3">
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
        <div class="row mt-3 mb-3">
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

    <div class="row mb-5">
        <div class="col-md-6">
            <img src="../book_covers/<?php echo $book->image ?>"
                 alt="Product Image" class="img-fluid rounded shadow-lg" style="width: 80%; height: auto;">
        </div>
        <div class="col-md-6">
            <h1 class="display-4 mb-4 text-primary"><?php echo $book->name; ?></h1>
            <h2 class="h4 mb-4 text-secondary"><?php echo $book->author; ?> | <?php echo $book->isbn; ?>
                | <?php echo $book->category; ?></h2>
            <p class="lead mb-4"><?php echo $book->desc; ?></p>

            <div class="row mt-3">
                <div class="col-md-12" id="res_display">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8">
                    <form id="res_form" action="/actions/book_actions.php" method="post">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <input type="hidden" name="book_id" id="book_id" value="<?php echo $_GET['book_id']; ?>">
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" name="date" id="date" required>
                            <button type="submit" class="input-group-text btn btn-primary" name="res_book">Reserve
                                Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/javascript.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.min.js"
        integrity="sha512-59nEVFc+y/gLPL2WjOAQqCbvZbs49xWdTiCxVpjeKhb8oyMviN8fcwFRl8VxVzeLfiH/NKpklQqqLFg0NdZBaQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let reserves = JSON.parse($('#reserves').html());
    let user_id = String($('#user_id').val());
    let type = $('#user_type').val();

    function checkReservations() {
        $('#res_form').submit(function (event) {
            let form_date = String($('#date').val());
            let isReserved = false;

            $.each(reserves, function (index, element) {
                let db_date = String(element.from_date);

                if (form_date === db_date) {
                    isReserved = true;
                    return false;
                }
            });

            if (isReserved) {
                event.preventDefault();
                Swal.fire({
                    title: 'Book reserved!',
                    text: "This book is already reserved for the selected date.",
                    icon: 'warning',
                });
            }
        });
    }

    function setDisplayReserves() {
        let html = '';

        if (reserves.length === 0) {
            html = '<h4 class="h4 text-secondary">Currently this book is not reserved by anyone.</h4>';
        } else {
            let rows = '';

            $.each(reserves, function (index, element) {
                let button = '';

                if (type === "Librarian" || String(element.user_id) === user_id) {
                    button = '<a href="/actions/book_actions.php?delete_res_id=' + element.res_id + '" class="btn btn-danger btn-sm">'
                        + '<i class="fa fa-trash" style="margin-right: 5px"></i> Remove Reservation'
                        + '</a>';
                }

                rows += '<tr>'
                    + '<td>' + element.from_date + '</td>'
                    + '<td>' + element.fname + ' ' + element.lname + '</td>'
                    + '<td>'
                    + button
                    + '</td>'
                    + '</tr>';
            });

            html = '<table class="table table-striped table-bordered">'
                + '<thead>'
                + '<tr>'
                + '<th>Date</th>'
                + '<th>Reserved By</th>'
                + '<th>Action</th>'
                + '</tr>'
                + '</thead>'
                + '<tbody>'
                + rows
                + '</tbody>'
                + '</table>';
        }

        $('#res_display').html(html);
    }

    $(document).ready(function () {
        setDisplayReserves();
        checkReservations();
    });
</script>
</body>
</html>

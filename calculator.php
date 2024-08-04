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
    ?>
    <?php include('includes/styles.php'); ?>
    <title>Library System - Fine Calculator</title>
</head>

<body>
<?php include('includes/navbar.php'); ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h1>Fine Calculator</h1>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="books_count"># of Books:</label>
                    <input type="number" id="books_count" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="days_count"># of Days:</label>
                    <input type="number" id="days_count" class="form-control" required>
                </div>
            </div>

            <div class="row mt-2 mb-3">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <button class="btn btn-primary form-control" id="calc">
                        <i class="fa fa-calculator"></i> Calculate
                    </button>
                </div>
            </div>

            <hr>

            <div class="row mt-3 text-end">
                <h3>Total fine: <span id="total">0</span>.00</h3>
            </div>
        </div>
    </div>
</div>

<?php include('includes/javascript.php'); ?>
<script>
    function calc() {
        let fine = 15;
        let books_count = $('#books_count').val();
        let days_count = $('#days_count').val();
        let total = books_count * days_count * fine;
        $('#total').html(total);
    }

    $(document).ready(function () {
        $('#calc').click(calc);
    });
</script>
</body>
</html>

<?php
require '../classes/BooksClass.php';

session_start();

if (isset($_POST['add_book_form'])) {
    $books = new Books();
    $books->addBook();
}

if (isset($_POST['edit_book_form'])) {
    $books = new Books();
    $books->updateBook();
}

if (isset($_POST['res_book'])) {
    $books = new Books();
    $books->reserveBook();
}

if (isset($_GET['delete_res_id'])) {
    $books = new Books();
    $books->removeReservation();
}
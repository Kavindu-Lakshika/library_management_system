<style>
    .btn-margin-right {
        margin-right: 10px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/">Library of Iowa State University</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"></a>
                </li>
            </ul>

            <div class="d-flex">
                <?php
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == '1') {
                ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user-circle" style="margin-right: 10px;"></i>
                                <?php echo $_SESSION['name']; ?>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item disabled"><?php echo $_SESSION['type']; ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/user_edit.php"><i class="fas fa-user" style="margin-right: 10px;"></i> Edit My Details</a>
                                <?php
                                if ($_SESSION['type'] == 'Librarian') {
                                    ?>
                                    <a class="dropdown-item" href="/add_books.php"><i class="fas fa-book" style="margin-right: 10px;"></i> Add Books</a>
                                    <a class="dropdown-item" href="/users.php"><i class="fas fa-users" style="margin-right: 10px;"></i> Users</a>
                                    <a class="dropdown-item" href="/calculator.php"><i class="fas fa-calculator" style="margin-right: 10px;"></i> Fine Calculator</a>
                                    <?php
                                }
                                ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/actions/auth_actoins.php?logout=true"><i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                <?php
                } else {
                ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a href="/" class="btn btn-success btn-margin-right my-2 my-sm-0 mr-2">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="/sign_up.php" class="btn btn-secondary my-2 my-sm-0">Sign Up</a>
                        </li>
                    </ul>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</nav>
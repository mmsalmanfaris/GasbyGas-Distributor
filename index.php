<?php
require_once './includes/config_session.inc.php';
require_once './includes/login_view.inc.php';
?>

<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css"
        integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">

    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/style.css">

    <title>Login GasbyGas</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    include_once './components/header-links.php';
    ?>
</head>

<body>
    <section class="vh-100">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card rounded-4">
                        <div class="card-body p-4 p-lg-5 text-black">
                            <form action="./includes/login.inc.php" method="post">

                                <div class="mb-5 text-center">
                                    <img src="./images/brand/logo.png" alt="Esai Studio Logo" style="height: 50px;">
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" id="username" name="username"
                                        class="form-control form-control-lg" />
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="form-control form-control-lg" />
                                </div>

                                <div class="pt-1 mb-4 text-center">
                                    <button class="btn btn-primary btn-lg btn-block w-100" type="submit">Login</button>
                                </div>

                                <?php
                                check_login_errors();
                                ?>
                            </form>
                            <ul>
                                <li><a href="register.php">Register User</a></li>
                                <li><a href="login.php">Login User</a></li>
                                <li><a href="fetch_users.php">Fetch Users</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    include_once './components/footer-links.php';
    ?>

</body>

</html>
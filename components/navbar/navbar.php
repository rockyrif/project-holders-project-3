<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .nav-group {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 3;
            background-color: transparent;
        }

        .responsive-brand-item {
            color: #000066;
            font-family: "PF Din Stencil W01 Bold";
        }

        .nav-link,
        .outer-button,
        .offcanvas-button {
            font-family: "PF Din Stencil W01 Bold";
        }

        .nav-link-home {
            color: #000066;
        }

        .nav-link-about {
            color: #91CC00;
        }

        .nav-link-contact {
            color: #000066;
        }

        .management {
            color: #91CC00;
        }

        @media only screen and (min-width: 992px) {
            .navbar-nav-outer {
                list-style: none;
                display: flex;
                align-items: center;
                margin-left: auto;
            }

            .nav-item-outer {
                padding: 10px;
            }

            .carousel-content {
                width: 65%;
            }
        }

        @media only screen and (max-width: 992px) {
            .navbar-nav-outer {
                display: none;
            }
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        @media only screen and (max-width: 480px) {
            .brand-br {
                display: block;
            }
        }

        .navbar-toggler {
            background-color: #198754;
        }

        .change-navbg-onScroll-in {
            background-color: white;
            transition: all 1s ease-in;
        }

        .change-navbg-onScroll-out {
            background-color: transparent;
            transition: all 1s ease-in-out;
        }

        .navbar-nav {
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Navbar start -->
    <div class="nav-group navbar-dark" id="navbar">
        <nav class="navbar">
            <div class="container">
                <a href="" class="navbar-brand fs-4">
                    <span class="container responsive-brand-item">
                        Pharmacy<span class="brand-br"><span class="management"> Management</span> System</span>
                    </span>
                </a>


                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#myNavBar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="myNavBar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav">
                            <!-- <li class="nav-item">
                                <a href="/project-holders-project-2/index.php" class="nav-link nav-link-home active">HOME</a>
                            </li>
                            <li class="nav-item">
                                <a href="/project-holders-project-2/page/AboutUs-page/About-us.php" class="nav-link nav-link-about">ABOUT US</a>
                            </li>
                            <li class="nav-item">
                                <a href="/project-holders-project-2/page/ContactUs-page/Contact-us.php" class="nav-link nav-link-contact">CONTACT</a>
                            </li> -->
                            <?php if (isset($_SESSION['username'])) : ?>
                                <li class="nav-item mt-2">
                                    <button type="button" class="btn btn-danger offcanvas-button" onclick="window.location.href='/project-holders-project-3/pages/login-and-signup-page/log-out.php'">LOG
                                        OUT</button>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Alert start -->
        <?php
        if (isset($_SESSION['response'])) {
            echo '<div id="alertContainer" class="alert alert-warning container alert-dismissible fade show" role="alert">
            ' . $_SESSION['response'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['response']);
        }
        ?>
        <script>
            // Automatically remove the alert after 4 seconds
            setTimeout(function() {
                var alertContainer = document.getElementById('alertContainer');
                if (alertContainer) {
                    alertContainer.remove();
                }
            }, 4000);
        </script>
        <script src="/project-holders-project-3/components/navbar/navbar.js"></script>
        <!-- Alert end -->
    </div>
    <!-- Navbar end -->


</body>

</html>
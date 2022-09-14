<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo.png">

    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="checkbox.js"></script>
    <script type="text/javascript" src="check_updater.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Oefenexamen</title>

</head>


<body>

    <?php
    if (isset($_SESSION["id"])) {
    ?>
        <header class="p-3 bg-dark text-white">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                            <use xlink:href="#bootstrap" />
                        </svg>
                    </a>
                    <a href="index.php"><img src="logo.png" alt="Logo" width="50" height="50"></a>
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="index.php" class="nav-link px-2 text-white">Homepagina</a></li>
                        <li><a href="school.php" class="nav-link px-2 text-white">School</a></li>
                        <li><a href="spelers.php" class="nav-link px-2 text-white">Spelers aanmelden</a></li>
                        <li><a href="spelers_update.php" class="nav-link px-2 text-white">Spelers bijwerken</a></li>
                        <li><a href="uitslag.php" class="nav-link px-2 text-white">Toernooien</a></li>
                        <li><a href="wedstrijden.php" class="nav-link px-2 text-white">Toernooi bijwerken</a></li>
                    </ul>

                    <div class="text-end">
                        <button type="button" class="btn btn-outline-light me-2">Medewerker: <?php echo $_SESSION["user_id"]; ?></button>
                        <a href="logout.php"><button class="btn btn-outline-light me-2">Log uit</button></a>
                        <a href="medewerker-signup.php"><button type="button" class="button btn btn-warning" style="background-color:Gray; border:2px solid White;">Registreer</button></a>
                    </div>
                </div>
            </div>
        </header>

    <?php
    } else {
    ?>
        <header class="p-3 bg-dark text-white">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                            <use xlink:href="#bootstrap" />
                        </svg>
                    </a>
                    <a href="index.php"><img src="logo.png" alt="Logo" width="50" height="50"></a>
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="index.php" class="nav-link px-2 text-white">Homepagina</a></li>
                    </ul>

                    <div class="text-end">
                        <a href="medewerker-login.php"><button type="submit" class="btn btn-outline-light me-2">Login</button></a>
                        <a onclick="return confirm('Vul de code in om een account aan te maken.')" href="signup-confirm.php"><button type="button" class="button btn btn-warning">Registreer</button></a>
                    </div>
                </div>
            </div>
        </header>
    <?php
    }
    ?>
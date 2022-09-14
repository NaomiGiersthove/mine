<?php require_once("header.php"); ?>

<div class="page1b">
    <main class="form-signin">
        <form action="signup-confirm.php" method="post">
            <h1 class="h3 mb-3 fw-normal">Vul de code in om te registreren.</h1>

            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="pwdC" placeholder="Password">
                <label for="floatingPassword">Code</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary btn-warning" type="submit" name="pwd-check">Enter</button>

        </form>
    </main>
</div>

<?php
if (isset($_POST["pwd-check"])) {

    $pwd = $_POST["pwdC"];

    if ($pwd == 1234) { //De medewerkers krijgen deze code om te registreren. 
        header("location: medewerker-signup.php");
    } else {
        $_SESSION['message'] = "Password Incorrect!";
        $_SESSION['msg_type'] = "danger";

        header("location: signup-confirm.php?=incorrect");
    }
}

?>


<?php require_once("footer.php"); ?>
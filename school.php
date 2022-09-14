<?php
require_once("header.php");

require "schooldatabase.php";
require "checker.php";
require "spelerdatabase.php";

try {
  $db = new PDO("mysql:host=localhost;dbname=examenoefen;", "root", "");
} catch (PDOException $exception) {
  echo "No database connection. Failed:" . $exception->getMessage() . "<br/>";
}

?>
<div class="container-sm">
  <div class="holder">
    <h1 class="title">Scholen</h1>
  </div>

  <div class="box-box">
    <br>
    <h2>Scholen:</h2>

    <?php
    postEachSchool($db);
    ?>
    <br>
  </div>

  <div class="box-box">
    <?php
    postRegistrationForm($db);
    ?>
  </div>

</div>

<?php require_once("footer.php");  ?>
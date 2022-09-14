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
    <h1 class="title">Gegevens speler aanpassen</h1>
  </div>
  <div class="box-box">
    <?php
    postEachPlayerForm($db);
    ?>
  </div>

</div>
<?php require_once("footer.php"); ?>
<?php
require_once("header.php");

require "schooldatabase.php";
require "checker.php";
require "spelerdatabase.php";

require "toernooidatabase.php";
require "toernooi.php";

try {
  $db = new PDO("mysql:host=localhost;dbname=examenoefen;", "root", "");
} catch (PDOException $exception) {
  echo "No database connection. Failed:" . $exception->getMessage() . "<br/>";
}

?>
<div class="container-sm">

  <div class="holder">
    <h1 class="title">Wedstrijden</h1>
    <br>
  </div>
  <div class="box-box">
    <?php
    if (isset($_REQUEST["t"])) {
      $toernooiID = $_REQUEST["t"];
      postWedstrijden($toernooiID, $db);
    } else {
      chooseToernooi("wedstrijden.php", $db);
    }

    ?>
  </div>
</div>

<?php require_once("footer.php"); ?>
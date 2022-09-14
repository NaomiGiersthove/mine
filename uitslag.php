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
    <h1 class="title">Uitslagen</h1>
    <br>
  </div>
  <div class="box-box1">
    <?php
    toernooiPage($db);
    ?>
  </div>

  <div class="box-box1">
    <br>
    <?php
    if (isset($_REQUEST["t"])) {
      $toernooiID = $_REQUEST["t"];
      postToernooi($toernooiID, $db);
    } else {
      chooseToernooi("uitslag.php", $db);
    }
    ?>
  </div>

</div>
<?php require_once("footer.php"); ?>
<?php require_once("header.php");
  require "schooldatabase.php";
  require "checker.php";
  require "spelerdatabase.php";

try {
  $db = new PDO("mysql:host=localhost;dbname=examenoefen;", "root", "");
} catch (PDOException $exception) {
  echo "No database connection. Failed:" . $exception->getMessage() . "<br/>";
}

?>

<main>

  <div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
      <p class="col-md-8 fs-4">Per toernooi kunnen er vier spelers of een veelvoud hiervan  meedoen.
    </div>
  </div>

</main>

<?php require_once("footer.php"); 
?>
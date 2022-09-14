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


$winnaar = $_REQUEST["w"];
$score1 = $_REQUEST["s1"];
$score2 = $_REQUEST["s2"];
$wedstrijdId = $_REQUEST["wID"];

if ($winnaar == 1) {
    $winnaarID = getSpelerIdGame(1, $wedstrijdId, $db);
} else {
    $winnaarID = getSpelerIdGame(2, $wedstrijdId, $db);
}
if ($winnaarID == "no result") {
    $winnaarID = 9;
}

$queryUpdate = $db->prepare("update wedstrijd set score1=?, score2=?, winnaar_id=? WHERE wedstrijd_id=?");
//winnaar
$result = $queryUpdate->execute(array($score1, $score2, $winnaarID, $wedstrijdId));
if ($result) {
    echo "succes";
} else {
    echo "fail";
}

require_once("footer.php");

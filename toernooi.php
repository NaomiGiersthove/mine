<?php
function updateRounds($toernooiId, $db) {
    $queryGetHighestRound = $db->prepare("SELECT MAX(ronde) as ronde FROM wedstrijd WHERE toernooi_id=?");
    $queryGetHighestRound->execute(array($toernooiId));

    $fetchRound = $queryGetHighestRound->fetch(PDO::FETCH_ASSOC);
    $newround = $fetchRound["ronde"] + 1;

    $queryGetOldWedstrijden = $db->prepare("SELECT * FROM `wedstrijd` WHERE ronde = (SELECT MAX(ronde) FROM wedstrijd WHERE toernooi_id='" . $toernooiId . "') AND toernooi_id='" . $toernooiId . "'");
    $queryGetOldWedstrijden->execute();

    $winnaarArr = array();

    foreach ($queryGetOldWedstrijden as $result) {
        array_push($winnaarArr, $result["winnaar_id"]);
    }

    if (count($winnaarArr) > 1) {
        for ($i = 0; $i < count($winnaarArr) - 1; $i += 2) {
            $winnaar1 = $winnaarArr[$i];
            $winnaar2 = $winnaarArr[$i + 1];

            $queryInsertWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=?, speler1_id=?, speler2_id=?");

            $queryInsertWed->execute(array($toernooiId, $newround, $winnaar1, $winnaar2));
        }
?>
        <script>
            location.reload();
        </script><?php
                } else {
                    if (count($winnaarArr) > 0) {
                        $winnaarTournament = $winnaarArr[0];

                        finishTournament($winnaarTournament, $toernooiId, $db);
                        $winnaar = getSpelerByID($winnaarTournament, $db);

                    ?>
            <div class="box-table">
                <h2> Dit toernooi is afgelopen. </h2>
                <br>
                <h4 class="green"> De winnaar van dit toernooi: <?php echo $winnaar["voornaam"] . " " . $winnaar["tussenvoegsel"] . " " . $winnaar["achternaam"]; ?></h4>
                <h4 class="green"> School: <?php echo $winnaar["schoolnaam"]; ?></h4>
            </div>
        <?php


                    } else {
        ?>
            <div class="box-table">
                <h1> Dit toernooi heeft geen spelers. </h1>
            </div>
<?php
                    }
                }
            }

            function finishTournament($winId, $tId, $db) {
                $queryFinishToernooi = $db->prepare("update toernooi set winnaar_id=?, afgesloten=1 WHERE toernooi_id=?");
                $queryFinishToernooi->execute(array($winId, $tId));
            }

            $arrCount = -1;

            function getNext($arr) {
                $GLOBALS["arrCount"] = $GLOBALS["arrCount"] + 1;

                if ($GLOBALS["arrCount"] <= count($arr) - 1) {
                    $index = $GLOBALS["arrCount"];

                    return $arr[$index];
                } else {
                    $GLOBALS["arrCount"] = 0;
                    $index = $GLOBALS["arrCount"];

                    return $arr[$index];
                }
            }

<?php
function toernooiPage($db) {
    if (isset($_POST["make"])) {
        postMakeToernooi($db);
    } else {
        if (isset($_POST["toernooiSubmit"])) {
            $toernooiname = $_POST["toernooiName"];
            $omschrijving = $_POST["omschrijving"];

            if (checkToernooi($toernooiname, $omschrijving, $db)) {

                insertToernooi($toernooiname, $omschrijving, $db);
                $toernooi_id = getIdFromToernooiName($toernooiname, $db);

                postAanmelden($toernooi_id, $db);
            }
        } else {
            if (isset($_POST["meldAan"])) {

                if (isset($_POST["speler_id"])) {
                    $toernooiId = $_POST["hiddenToernooi"];
                    $spelerIdArr = $_POST["speler_id"];
                    $succesfulQueries = 0;

                    foreach ($spelerIdArr as $spelerId) {
                        if (insertAanmelding($spelerId, $toernooiId, $db)) {
                            $succesfulQueries++;
                        }
                    }

                    if ($succesfulQueries == count($spelerIdArr)) {
?><br>
                        <div class="box-terug">
                            <h2> Dit toernooi is aangemaakt.</h2><br><a class="whitetext" href="uitslag.php"> Terug </a><br><br>
                        </div><?php
                            } else {
                                ?><br>
                        <div class="box-terug">
                            <h2> Er ging iets mis.</h2><br><a class="whitetext" href="uitslag.php"> Terug </a><br><br>
                        </div><?php
                            }

                            makeWedstrijden($toernooiId, $db);
                            header("Location: uitslag.php?t=" . $toernooiId);
                        }
                    } else {
?>
                <div class="box-table2">
                    <form action="" method="post">
                        <h1>Maak een toernooi:</h1>
                        <input type="submit" name="make" class="submit-btn" value="Maak een nieuw toernooi aan">
                        <br>
                        <br>
                    </form>
                </div>
    <?php
                    }
                }
            }
        }

        function postMakeToernooi($db) {
    ?>
    <div class="box-table2">
        <form method="post" action="">
            <h1>Naam van het toernooi:</h1>
            <input type="text" class="text-input" name="toernooiName">
            <br><br>
            <h1>Omschrijving:</h1>
            <textarea name="omschrijving" rows="5" cols="40"></textarea>
            <br>
            <input type="submit" class="submit-btn" name="toernooiSubmit" value="Aanmaken">
            <br><br>
        </form>
        <?php
        }

        function checkToernooi($toernooiname, $omschrijving, $db) {
            if (!$toernooiname) {
                echo "<h2> Geef het toernooi een naam.</h2>";
                return false;
            } else {
                if (strlen($toernooiname) > 50) {
        ?><br>
                <div class="box-terug">
                    <h2> De naam is te lang</h2><br><a class="whitetext" href="uitslag.php"> Terug </a><br><br>
                </div><?php
                        return false;
                    } else {
                        if (strlen($omschrijving) > 100) {
                        ?><br>
                    <div class="box-terug">
                        <h2> De omschrijving is te lang.</h2><br><a class="whitetext" href="uitslag.php"> Terug </a><br><br>
                    </div><?php
                            return false;
                        } else {
                            if (TableHas("toernooi", $toernooiname, "toernooi_naam", $db)) {
                            ?><br>
                        <div class="box-terug">
                            <h2> Dit toernooi bestaat al. Geef het een andere naam.</h2><br><a class="whitetext" href="uitslag.php"> Terug </a><br><br>
                        </div><?php
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }

            function insertToernooi($toernooiname, $omschrijving, $db) {
                $queryToernooi = $db->prepare("insert into toernooi set toernooi_naam=?, omschrijving=?");
                $queryToernooi->execute(array($toernooiname, $omschrijving));
            }

            function insertAanmelding($spelerId, $toernooiId, $db) {
                $queryAanmelding = $db->prepare("insert into aanmeldingen set speler_id=?, toernooi_id=?");
                $result = $queryAanmelding->execute(array($spelerId, $toernooiId));

                if ($result) {
                    return true;
                } else {
                    return false;
                }
            }

            function getIdFromToernooiName($name, $db) {
                $querySelect = $db->prepare("select toernooi_id from toernooi where toernooi_naam=?");
                $querySelect->execute(array($name));

                $rows = $querySelect->rowCount();

                if ($rows > 0) {
                    $fetch = $querySelect->fetch(PDO::FETCH_ASSOC);
                    $idRes = $fetch["toernooi_id"];
                    return $idRes;
                } else {
                    return "no toernooi";
                }
            }

            function postAanmelden($toernooiId, $db) {
                                ?>
        <div class="box-players">
            <?php
                $toernooi = getToernooiById($toernooiId, $db);
            ?>
            <h3> Naam toernooi: <?php echo $toernooi["toernooi_naam"]; ?></h3>
            <h3> Omschrijving: <?php echo $toernooi["omschrijving"]; ?></h3>
            <h3> Datum: <?php echo $toernooi["datum"]; ?></h3>
        </div>
        <div class="box-players">
            <form method="post" action="">
                <br>
                <input type="hidden" value="<?php echo $toernooiId ?>" name="hiddenToernooi">

                <input type="submit" name="meldAan" value="Submit gecheckte spelers" class="submit-btn">
                <br><br>
                <div class="submit-btn" onclick="toggleCheckBoxes()" style="user-select: none;">Selecteer alle spelers</div>
        </div>
        <?php
                $querySpeScho = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id");
                $querySpeScho->execute();
                foreach ($querySpeScho as $player) {
        ?>
            <div class="box-players">
                <h4> Naam: <?php echo $player["voornaam"] . " " . $player["tussenvoegsel"] . " " . $player["achternaam"]; ?></h4>
                <h4> School: <?php echo $player["schoolnaam"]; ?></h4>
                <input type="checkbox" name="speler_id[]" value="<?php echo $player["speler_id"]; ?>" class="checkBox" />
            </div>
        <?php
                }
        ?>
        </form>
    <?php
            }

            function getToernooiById($idToer, $db) {
                $querySelect = $db->prepare("select * from toernooi where toernooi_id=?");
                $querySelect->execute(array($idToer));

                $rows = $querySelect->rowCount();

                if ($rows > 0) {
                    $fetch = $querySelect->fetch(PDO::FETCH_ASSOC);
                    return $fetch;
                } else {
                    return "no toernooi";
                }
            }

            function chooseToernooi($link, $db) {
    ?> <h1> Kies een toernooi: </h1> <?php
                                    $link = $link . "?t=";

                                    $queryToernooi = $db->prepare("select * from toernooi");
                                    $queryToernooi->execute();
                                    foreach ($queryToernooi as $toernooi) {
                                    ?>
            <div class="box-toernooien">
                <h4> Naam toernooi: <?php echo $toernooi["toernooi_naam"]; ?></h4>
                <h4> Omschrijving: <?php echo $toernooi["omschrijving"]; ?></h4>
                <h4> Datum toernooi: <?php echo $toernooi["datum"]; ?></h3>
                    <div class="submit-btn2"><a style="margin-left:10%;" href="<?php echo $link . $toernooi["toernooi_id"]; ?>" class="whitetext">Uitslag</a></div>
                    <br><br>
            </div>
        <?php
                                    }
                                }

                                function postToernooi($idT, $db)
                                {
                                    $queryGetWinner = $db->prepare("SELECT * FROM toernooi INNER JOIN spelers ON toernooi.winnaar_id = spelers.speler_id WHERE toernooi_id=?");
                                    $queryGetWinner->execute(array($idT));
                                    $fetchWinner = $queryGetWinner->fetch(PDO::FETCH_ASSOC);
                                    if ($fetchWinner) {
        ?>
            <h1> Winnaar toernooi: <?php echo $fetchWinner["voornaam"] . " " . $fetchWinner["tussenvoegsel"] . " " . $fetchWinner["achternaam"]; ?></h1>
            <?php
                                    }
                                    $queryGetHighestRound = $db->prepare("SELECT MAX(ronde) as ronde FROM wedstrijd WHERE toernooi_id=?");
                                    $queryGetHighestRound->execute(array($idT));

                                    $fetchRound = $queryGetHighestRound->fetch(PDO::FETCH_ASSOC);
                                    $highestRound = $fetchRound["ronde"];

                                    if ($highestRound) {

                                        for ($i = 0; $i < $highestRound; $i++) {
            ?>
                <div class="box-round">
                    <div class="ronde">
                        <h1> Ronde: <?php echo $i + 1; ?> </h1>
                    </div>
                    <br>
                    <?php
                                            $queryGetWedstrijden = $db->prepare("SELECT * FROM wedstrijd WHERE toernooi_id=? AND ronde=? ORDER BY winnaar_id ASC");
                                            $queryGetWedstrijden->execute(array($idT, $i + 1));

                                            foreach ($queryGetWedstrijden as $wedstrijd) {
                                                $speler1 = getSpelerByID($wedstrijd["speler1_id"], $db);
                                                $speler2 = getSpelerByID($wedstrijd["speler2_id"], $db);
                                                $winnaar = getSpelerByID($wedstrijd["winnaar_id"], $db);


                    ?>
                        <div class="box-wedstrijden2">
                            <?php
                                                if ($speler1) {
                                                    if ($wedstrijd["winnaar_id"] == $wedstrijd["speler1_id"]) {
                            ?>
                                    <h4 class="green"> <?php echo $speler1["voornaam"] . " " . $speler1["tussenvoegsel"] . " " . $speler1["achternaam"]; ?></h4>
                                    <h4 class="green">School: <?php echo $speler1["schoolnaam"]; ?></h4><?php
                                                                                                    } else {
                                                                                                        if ($winnaar) {
                                                                                                        ?>
                                        <h4 class="red"> <?php echo $speler1["voornaam"] . " " . $speler1["tussenvoegsel"] . " " . $speler1["achternaam"]; ?></h4>
                                        <h4 class="red"> School: <?php echo $speler1["schoolnaam"]; ?></h4>
                                    <?php
                                                                                                        } else {
                                    ?>
                                        <h4> <?php echo $speler1["voornaam"] . " " . $speler1["tussenvoegsel"] . " " . $speler1["achternaam"]; ?></h4>
                                        <h4> School: <?php echo $speler1["schoolnaam"]; ?></h4>
                                <?php
                                                                                                        }
                                                                                                    }
                                                                                                } else {
                                ?>
                            <?php
                                                                                                }
                            ?>
                            <h2 class="tp1"> VS </h2>
                            <?php
                                                if ($speler2) {

                                                    if ($wedstrijd["winnaar_id"] == $wedstrijd["speler2_id"]) {
                            ?>
                                    <h4 class="green"><?php echo $speler2["voornaam"] . " " . $speler2["tussenvoegsel"] . " " . $speler2["achternaam"]; ?></h4>
                                    <h4 class="green">School: <?php echo $speler2["schoolnaam"]; ?></h4>
                                    <?php
                                                    } else {
                                                        if ($winnaar) {
                                    ?>
                                        <h4 class="red"> <?php echo $speler2["voornaam"] . " " . $speler2["tussenvoegsel"] . " " . $speler2["achternaam"]; ?></h4>
                                        <h4 class="red"> School: <?php echo $speler2["schoolnaam"]; ?></h4>
                                    <?php
                                                        } else {
                                    ?>
                                        <h4> <?php echo $speler2["voornaam"] . " " . $speler2["tussenvoegsel"] . " " . $speler2["achternaam"]; ?></h3>
                                            <h4> School: <?php echo $speler2["schoolnaam"]; ?></h4>
                                    <?php
                                                        }
                                                    }
                                                } else {
                                    ?>
                                <?php
                                                }

                                                if ($winnaar) {
                                ?>
                                    <br>
                                    <h3 class="green"> Winnaar: <?php echo $winnaar["voornaam"] . " " . $winnaar["tussenvoegsel"] . " " . $winnaar["achternaam"]; ?></h3>
                                <?php
                                                } else {
                                ?>
                                    <br>
                                    <h3 class="green"> Winnaar: Nog geen winnaar bekend</h3>

                                <?php
                                                }

                                                if ($wedstrijd["score1"] > -1) {
                                ?>
                                    <h3 style="color: green"> Score: <?php echo $wedstrijd["score1"] . "-" . $wedstrijd["score2"]; ?></h3>
                                    <br>
                                <?php
                                                } else {
                                ?>
                                    <h3 style="color: green"> Score: Nog niet bekend</h3>
                                    <br>
                            <?php

                                                }
                                            }
                            ?>
                        </div>
                    <?php

                                        }
                                    } else {
                    ?><div class="box-table">
                        <h1> Empty </h1>
                    </div><?php
                                    }
                                }

                                function makeWedstrijden($toernooiId, $db)
                                {

                                    $spelerArr = getSpelerArr($toernooiId, $db);

                                    for ($i = 0; $i < count($spelerArr) - 1; $i += 2) {
                                        echo $spelerArr[$i]["voornaam"] . " " . $spelerArr[$i + 1]["voornaam"] . "//";
                                        //make a wedstrijd
                                        insertIntoWedstrijdTable($toernooiId, $spelerArr[$i]["speler_id"], $spelerArr[$i + 1]["speler_id"], $db);
                                    }
                                }

                                function insertIntoWedstrijdTable($toernooiId, $speler1, $speler2, $db)
                                {

                                    if ($speler1 == 0) {
                                        $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler2_id=?, winnaar_id=?");
                                        $queryWed->execute(array($toernooiId, $speler2, $speler2));
                                    } else {
                                        if ($speler2 == 0) {
                                            $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler1_id=?, winnaar_id=?");
                                            $queryWed->execute(array($toernooiId, $speler1, $speler1));
                                        } else {
                                            $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler1_id=?, speler2_id=?");

                                            $queryWed->execute(array($toernooiId, $speler1, $speler2));
                                        }
                                    }
                                }

                                function getSpelerArr($toernooiId, $db)
                                {

                                    $playerArr = array();
                                    $queryAanmelding = $db->prepare("SELECT * FROM `aanmeldingen` where toernooi_id = " . $toernooiId);
                                    $queryAanmelding->execute(array());
                                    foreach ($queryAanmelding as $aanmelding) {
                                        $querySpelers = $db->prepare("SELECT * FROM `spelers` where speler_id = " . $aanmelding["speler_id"]);
                                        $querySpelers->execute(array());
                                        $spelerFetch = $querySpelers->fetch(PDO::FETCH_ASSOC);
                                        array_push($playerArr, $spelerFetch);
                                    }

                                    $arrayWithDummies = getDummyArray($playerArr);
                                    return $arrayWithDummies;
                                }

                                function getDummyArray($pArr)
                                {

                                    $dummyArray = $pArr;
                                    $finalCnt = 2;
                                    while ($finalCnt < count($pArr)) {
                                        $finalCnt = $finalCnt * 2;
                                    }

                                    $dummy = array(
                                        "speler_id" => 0,
                                        "voornaam" => "Dummy",
                                        "tussenvoegsel" => "",
                                        "achternaam" => "freepass",
                                        "school_id" => "test"
                                    );

                                    while (count($dummyArray) < $finalCnt) {
                                        array_push($dummyArray, $dummy);
                                    }

                                    if ($finalCnt < 128) {
                                        while (!isRandomDummy($dummyArray)) {
                                            shuffle($dummyArray);
                                        }
                                    } else {
                                        shuffle($dummyArray);
                                    }
                                    return $dummyArray;
                                }

                                function isRandomDummy($arr)
                                {

                                    for ($i = 0; $i < count($arr) - 1; $i += 2) {

                                        if ($arr[$i]["speler_id"] == $arr[$i + 1]["speler_id"]) {
                                            return false;
                                        }
                                    }
                                    return true;
                                }

                                function postWedstrijden($toernooiId, $db)
                                {
                                    $queryCurrRound = $db->prepare("SELECT * FROM `wedstrijd` WHERE toernooi_id='" . $toernooiId . "' 
    AND ronde = (SELECT MIN(ronde) FROM wedstrijd WHERE toernooi_id='" . $toernooiId . "' AND winnaar_id IS NULL) ORDER BY `wedstrijd`.`winnaar_id` ASC");
                                    $queryCurrRound->execute(array());

                                    $queryGetRonde = $db->prepare("SELECT * FROM `wedstrijd` WHERE toernooi_id='" . $toernooiId . "' 
    AND ronde = (SELECT MIN(ronde) FROM wedstrijd WHERE toernooi_id='" . $toernooiId . "' AND winnaar_id IS NULL) ORDER BY `wedstrijd`.`winnaar_id` ASC");
                                    $queryGetRonde->execute(array());
                                    $fetchRonde = $queryGetRonde->fetch(PDO::FETCH_ASSOC);
                                    $rowsRonde = $queryGetRonde->rowCount();

                                    if ($rowsRonde > 0) {
                            ?>
                    <h4>Selecteer de gespeelde score en druk op 'Bevestig score'</h4>
                    <button onclick="submitValues();" class="submit-btn4">Bevestig score</button>
                    <button class="submit-btn" " onclick=" location.reload();">Volgende ronde></button>
                    <br>
                <?php
                                    }
                                    if ($rowsRonde > 0) {
                ?>
                    <h2>Ronde: <?php echo $fetchRonde["ronde"]; ?></h1>
                        <br>
                        <?php
                                        $count = 0;
                                        $array = array();
                                        foreach ($queryCurrRound as $wedstrijd) {
                                            $count++;
                                            array_push($array, $wedstrijd);
                                            $speler1 = getSpelerByID($wedstrijd["speler1_id"], $db);
                                            $speler2 = getSpelerByID($wedstrijd["speler2_id"], $db);
                                            $winnaar = getSpelerByID($wedstrijd["winnaar_id"], $db);

                                            if (!$winnaar) {
                        ?>
                                <div class="box-wedstrijden">
                                <?php

                                            } else {
                                ?>
                                    <div class="box-wedstrijden-dark">
                                        <h2 class="green"> Ronde: <?php echo $wedstrijd["ronde"]; ?></h2>
                                        <?php
                                            }
                                            if ($speler1) {
                                                if ($wedstrijd["winnaar_id"] == $wedstrijd["speler1_id"]) {
                                        ?>
                                            <h4 class="green"> <?php echo $speler1["voornaam"] . " " . $speler1["tussenvoegsel"] . " " . $speler1["achternaam"]; ?>
                    </h2>
                    <h4 class="green"> <?php echo $speler1["schoolnaam"]; ?></h4><?php
                                                                                } else {
                                                                                    if ($winnaar) {
                                                                                    ?>
                        <h4 class="red"> <?php echo $speler1["voornaam"] . " " . $speler1["tussenvoegsel"] . " " . $speler1["achternaam"]; ?></h2>
                            <h4 class="red"> School: <?php echo $speler1["schoolnaam"]; ?></h4>
                        <?php
                                                                                    } else {
                        ?>
                            <h4> <?php echo $speler1["voornaam"] . " " . $speler1["tussenvoegsel"] . " " . $speler1["achternaam"]; ?></h2>
                                <h4> School: <?php echo $speler1["schoolnaam"]; ?></h4>
                        <?php
                                                                                    }
                                                                                }
                                                                            } else {
                        ?>
                    <?php
                                                                            }
                    ?>
                    <h1 class="tp1"> VS </h1>
                    <?php
                                            if ($speler2) {
                                                if ($wedstrijd["winnaar_id"] == $wedstrijd["speler2_id"]) {
                    ?>
                            <h4 class="green"> <?php echo $speler2["voornaam"] . " " . $speler2["tussenvoegsel"] . " " . $speler2["achternaam"]; ?></h2>
                                <h4 class="green"> School: <?php echo $speler2["schoolnaam"]; ?></h4>
                                <?php
                                                } else {
                                                    if ($winnaar) {
                                ?>
                                    <h4 class="red"> <?php echo $speler2["voornaam"] . " " . $speler2["tussenvoegsel"] . " " . $speler2["achternaam"]; ?></h4>
                                    <h4 class="red"> School: <?php echo $speler2["schoolnaam"]; ?></h2>
                                    <?php
                                                    } else {
                                    ?>
                                        <h4> <?php echo $speler2["voornaam"] . " " . $speler2["tussenvoegsel"] . " " . $speler2["achternaam"]; ?></h4>
                                        <h4> School: <?php echo $speler2["schoolnaam"]; ?></h4>
                                <?php
                                                    }
                                                }
                                            } else {
                                ?>
                            <?php
                                            }
                                            if ($winnaar) {
                            ?>
                                <br>
                                <h2 class="green"> Winnaar: <?php echo $winnaar["voornaam"] . " " . $winnaar["tussenvoegsel"] . " " . $winnaar["achternaam"]; ?></h2>
                            <?php
                                            } else {
                            ?>
                                <br>
                                <h2 class="green"> Winnaar: Nog niet bekend</h2>
                            <?php
                                            }

                                            if ($wedstrijd["score1"] > -1) {
                            ?>
                                <h2 style="color: green"> Score: <?php echo $wedstrijd["score1"] . "-" . $wedstrijd["score2"]; ?></h2>
                                <?php
                                            } else {
                                                if ($speler1 && $speler2) {
                                ?>
                                    <h2 style="color: green"> Score: </h2>
                                    <select name="score[]" class="text-input2" id="<?php echo $wedstrijd["wedstrijd_id"]; ?>">
                                        <option> 1 - 0 </option>
                                        <option> 2 - 0 </option>
                                        <option> 3 - 0 </option>

                                        <option> 0 - 1 </option>
                                        <option> 1 - 1 </option>
                                        <option> 2 - 1 </option>
                                        <option> 3 - 1 </option>

                                        <option> 0 - 2 </option>
                                        <option> 1 - 2 </option>
                                        <option> 2 - 2 </option>
                                        <option> 3 - 2 </option>

                                        <option> 0 - 3 </option>
                                        <option> 1 - 3 </option>
                                        <option> 2 - 3 </option>
                                        <option> 3 - 3 </option>
                                    </select>

                                    <br><br>
                                <?php
                                                } else {
                                ?>
                                    <h2 style="color: green"> Score: No Score</h2>
                <?php
                                                }
                                            }
                                        }
                                    } else {
                                        updateRounds($toernooiId, $db);
                                    }
                                }
                ?>
<?php

function getSpelerCnt($db) {

    $query_spelers = $db->prepare("SELECT * from spelers");
    $query_spelers->execute();
    $rows = $query_spelers->rowCount();
    return $rows;
}

function insertSchoolIntoDb($school, $db) {

    $query_school_insert =  $db->prepare("INSERT INTO `scholen` (`schoolnaam`) VALUES ('" . $school . "')");

    $result = $query_school_insert->execute();

    if ($result) {
        echo "<h2> De school is succesvol geregistreerd.</h2>";
        header("Refresh:0");
    } else {
        echo "<h2> Er is wat mis gegaan.</h2>";
    }
}

function postEachPlayerForm($db) {

    if (isset($_POST["wijzig"])) {
        $hiddenId = $_POST["hiddenId"];
        postWijzigForm($hiddenId, $db);
    } else {
        if (isset($_POST["verwijder"])) {
            $hiddenId = $_POST["hiddenId"];
            deletePlayer($hiddenId, $db);
        } else {
            if (isset($_POST["submit"])) {
                $speler_id = $_POST["speler_id"];
                $voornaam = $_POST["voornaam"];
                $tussenvoegsel = $_POST["tussenvoegsel"];
                $achternaam = $_POST["achternaam"];
                $schoolnaam = $_POST["school"];

                $school_id = getIdFromSchoolName($schoolnaam, $db);

                if (checkValidityName($voornaam, $tussenvoegsel, $achternaam, $school_id, $db, 0)) {

                    $query_speler =  $db->prepare("UPDATE `spelers` set voornaam=?, tussenvoegsel=?, achternaam=?, school_id=? WHERE speler_id=?");
                    $query_speler->execute(array(
                        $voornaam,
                        $tussenvoegsel,
                        $achternaam,
                        $school_id,
                        $speler_id
                    ));

                    $result = $query_speler->execute();

                    if ($result) {
?><br>
                        <div class="box-terug">
                            <h2> Speler succesvol geupdate.</h2><br><a class="whitetext" href="spelers_update.php"> Terug </a><br><br>
                        </div><?php
                            } else {
                                echo "<h2> Er is iets fout gegaan. Probeer het nog een keer.</h2>";
                            }
                        }
                    } else {
                        $querySpeScho = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id");
                        $querySpeScho->execute();
                                ?>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>School</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <?php
                        foreach ($querySpeScho as $player) {
                    ?>
                        <tr>
                            <form method="post" action="">
                                <td> <?php echo $player["voornaam"] . " " . $player["tussenvoegsel"] . " " . $player["achternaam"]; ?></td>
                                <td> <?php echo $player["schoolnaam"]; ?></td>
                                <td>
                                    <input type="hidden" value="<?php echo $player["speler_id"]; ?>" name="hiddenId">
                                    <input type="submit" name="wijzig" value="Update" class="submit-btn2">
                                    <input type="submit" name="verwijder" value="Delete" class="submit-btn2">
                            </form>
                            </div>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                </table>
    <?php
                    }
                }
            }
        }


        function postWijzigForm($id, $db)
        {
            $querySpelers = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id where spelers.speler_id = " . $id);
            $querySpelers->execute();

            $fetchedPlayer = $querySpelers->fetch(PDO::FETCH_ASSOC);

    ?>
    <div class="box-table">
        <br>
        <form method="post" action="">
            <input type="hidden" value="<?php echo $id; ?>" name="speler_id">
            <h2> Nieuwe Voornaam:</h2>
            <input type="text" class="text-input2" name="voornaam" placeholder="<?php echo $fetchedPlayer["voornaam"]; ?>" value="<?php echo $fetchedPlayer["voornaam"]; ?>">
            <h2>Nieuwe Tussenvoegsel:</h2>
            <input type="text" class="text-input3" name="tussenvoegsel" placeholder="<?php echo $fetchedPlayer["tussenvoegsel"]; ?>" value="<?php echo $fetchedPlayer["tussenvoegsel"]; ?>">
            <h2>Nieuwe Achternaam:</h2>
            <input type="text" class="text-input2" name="achternaam" placeholder="<?php echo $fetchedPlayer["achternaam"]; ?>" value="<?php echo $fetchedPlayer["achternaam"]; ?>">
            <h2>Nieuwe School:</h2>

            <?php
            $query_schools = $db->prepare("SELECT * from scholen");
            $query_schools->execute();
            ?>
            <select name="school" class="text-input2" placeholder="<?php echo $fetchedPlayer["schoolnaam"]; ?>" value="<?php echo $fetchedPlayer["schoolnaam"]; ?>">

                <?php
                foreach ($query_schools as $school) {
                    $schoolnaam = $school["schoolnaam"];
                ?> <option> <?php echo $schoolnaam; ?></option>
                <?php
                }
                ?>
            </select>
            <br>
            <br>
            <input type="submit" class="submit-btn" name="submit" value="Update speler">
            <br><br>
        </form>
    </div>
    <?php
        }
        function deletePlayer($speler_id, $db) {
            $query_del = $db->prepare("DELETE FROM `spelers` WHERE `spelers`.`speler_id` = ?");
            $result = $query_del->execute(array($speler_id));

            if ($result) {
    ?><br>
        <div class="box-terug">
            <h2> Deze speler is verwijderd.</h2><br><a class="whitetext" href="spelers_update.php"> Terug </a><br><br>
        </div><?php
            } else {
                ?><br>
        <div class="box-terug">
            <h2> Deze speler kan niet worden verwijderd.</h2><br><a class="whitetext" href="spelers_update.php"> Terug </a><br><br>
        </div><?php
            }
        }


        function getSpelerByID($sId, $db) {
            $query_select = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id where speler_id='" . $sId . "'");
            $query_select->execute();

            $rows = $query_select->rowCount();

            if ($rows > 0) {
                $speler = $query_select->fetch(PDO::FETCH_ASSOC);
                return $speler;
            }
        }

        function getSpelerIdGame($speler, $wedstrijdId, $db) {
            if ($speler == 1) {
                $query_select = $db->prepare("SELECT speler1_id FROM `wedstrijd` WHERE wedstrijd_id = ?");
                $query_select->execute(array($wedstrijdId));

                $rows = $query_select->rowCount();

                if ($rows > 0) {
                    $speler = $query_select->fetch(PDO::FETCH_ASSOC);
                    return $speler["speler1_id"];
                } else {
                    return "no result";
                }
            } else {
                $query_select = $db->prepare("SELECT speler2_id FROM `wedstrijd` WHERE wedstrijd_id = ?");
                $query_select->execute(array($wedstrijdId));

                $rows = $query_select->rowCount();

                if ($rows > 0) {
                    $speler = $query_select->fetch(PDO::FETCH_ASSOC);
                    return $speler["speler2_id"];
                } else {
                    return "no result";
                }
            }
        }

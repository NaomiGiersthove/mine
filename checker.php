<?php
function postRegistrateFormPlayer($db, $splrcnt) {
?>
    <br>
    <form method="post" action="">
        <h3>Voornaam:</h3>
        <input type="text" class="text-input2" name="voornaam">
        <h3>Tussenvoegsel:</h3>
        <input type="text" class="text-input3" name="tussenvoegsel">
        <h3>Achternaam:</h3>
        <input type="text" class="text-input2" name="achternaam">
        <h2>School:</h1>
            
            <?php

            $query_schools = $db->prepare("SELECT * from scholen");
            $query_schools->execute();

            ?>

            <select name="school" class="text-input2">
                
                <?php

                foreach ($query_schools as $school) {
                    $schoolnaam = $school["schoolnaam"];

                    ?> 
                        <option>
                            <?php echo $schoolnaam; ?>
                        </option>

                    <?php
                }

                ?>

            </select>

            <br>
            <br>
            <input type="submit" class="submit-btn" name="submit" value="Aanmelden ">
            <br><br>

    </form>
<?php

    if (isset($_POST["submit"])) {
        $voornaam = $_POST["voornaam"];
        $tussenvoegsel = $_POST["tussenvoegsel"];
        $achternaam = $_POST["achternaam"];
        $schoolnaam = $_POST["school"];

        $school_id = getIdFromSchoolName($schoolnaam, $db);

        if ($splrcnt < 128) {

            if (checkValidityName($voornaam, $tussenvoegsel, $achternaam, $school_id, $db, 1)) {
                $query_speler =  $db->prepare("INSERT INTO `spelers` set voornaam=?, tussenvoegsel=?, achternaam=?, school_id=?");
                $result = $query_speler->execute(array(
                    $voornaam,
                    $tussenvoegsel,
                    $achternaam,
                    $school_id
                ));

                if ($result) {
                    echo "<h2> De nieuwe speler is succesvol geregistreerd.</h2>";
                } else {
                    echo "<h2> Er is een error.</h2>";
                }
            }
        } else {
            echo "<h2> Limiet spelers overschreden.</h2>";
            return false;
        }
    }
}


function checkValidityName($voornaam, $tussenvoegsel, $achternaam, $school, $db, $registratie) {

    if (!$voornaam || !$achternaam) {
        echo "<h2>Vul alle velden in.</h2>";
        return false;
    } else {
        if (strlen($voornaam) > 50 || strlen($achternaam) > 50 || strlen($tussenvoegsel) > 20) {
            echo "<h2>Te veel tekens ingevuld bij voornaam, tussenvoegsel of achternaam</h2>";
            return false;
        } else {

            $query_select = "";
            if (!$tussenvoegsel) {
                $query_select = $db->prepare("select* from spelers where voornaam='" . $voornaam . "' and achternaam='" . $achternaam . "' and school_id='" . $school . "'");
            } else {
                $query_select = $db->prepare("select* from spelers where voornaam='" . $voornaam . "' and achternaam='" . $achternaam . "' and tussenvoegsel='" . $tussenvoegsel . "' and school_id='" . $school . "'");
            }

            $query_select->execute();

            $rows = $query_select->rowCount();

            if ($rows > 0) {

                if ($registratie == 1) {
                    echo "<h2>Deze speler is al geregistreerd</h2>";
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
    }
}


function getIdFromSchoolName($name, $db) {

    $query_select = $db->prepare("select school_id from scholen where schoolnaam='" . $name . "'");
    $query_select->execute();
    $rows = $query_select->rowCount();

    if ($rows > 0) {
        $school_id_fetch = $query_select->fetch(PDO::FETCH_ASSOC);
        $school_id = $school_id_fetch["school_id"];

        return $school_id;
    } else {
        return "Er is geen school beschikbaar";
    }
}

?>
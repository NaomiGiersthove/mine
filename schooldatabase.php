<?php
function postEachSchool($db) {
    $query_schools = $db->prepare("SELECT * from scholen");
    $query_schools->execute();
?>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>School</th>
            </tr>
        </thead>
        <?php
        foreach ($query_schools as $school) {
        ?>
            <tr>
                <td><?php echo $school["schoolnaam"]; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}

function postRegistrationForm($db) {
?>
    <br>
    <form method="post" action="">
        <h3>Schoolnaam:</h3>
        <input type="text" class="text-input" name="school-name">
        <br><br>
        <input type="submit" class="submit-btn" name="submit" value="Submit">
        <br><br>
    </form>
<?php
    if (isset($_POST["submit"])) {
        $school = $_POST["school-name"];

        checkValiditySchool($school, $db);
    }
}

function checkValiditySchool($school, $db) {

    if (!$school) {
        echo "<h2> Dit veld is niet ingevuld</h2>";
    } else {
        if (strlen($school) > 50) {
            echo "<h2>De naam van de school is te lang.</h2>";
        } else {
            if (TableHas("scholen", $school, "schoolnaam", $db)) {
                echo "<h2>Deze school is al geregistreed.</h2>";
            } else {
                if ($school != "test") {
                    $query_school_insert =  $db->prepare("INSERT INTO `scholen` (`schoolnaam`) VALUES ('" . $school . "')");

                    $result = $query_school_insert->execute();

                    if ($result) {
                        echo "<h2> School succesvol geregistreerd.</h2>";
                        header("Refresh:0");
                    } else {
                        echo "<h2> Er is iets fout gegaan.</h2>";
                    }
                } else {
                    echo "<h2>Vul een andere naam in.</h2>";
                }
            }
        }
    }
}

function TableHas($table, $needle, $colomn, $db) {
    $query_select = $db->prepare("select* from " . $table . " where " . $colomn . "='" . $needle . "'");
    $query_select->execute(array());

    $rows = $query_select->rowCount();

    if ($rows > 0) {
        return true;
    } else {
        return false;
    }
}

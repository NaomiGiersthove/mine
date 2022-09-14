<?php
class LoginDatabase extends Dbh {
    protected function getUser($userid, $pwd)
    {
        // Dit neemt een user aan de databse.
        $stmt = $this->connect()->prepare('SELECT user_pwd FROM worker WHERE user_id = ? or user_email = ?;'); //select statement !!!

        if (!$stmt->execute(array($userid, $pwd))) {
            $stmt = null;
            header("location: medewerker-login.php?error=stmtfailed"); // !!!
            exit();
        }

        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: medewerker-login.php?error=usernotfound"); // !!!
            exit();
        }

        // zoek als user of email en password is gelijk in database.
        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = $pwdHashed; // !!!

        if ($checkPwd == false) {
            $stmt = null;
            header("location: medewerker-login.php?error=wrongpassword"); // !!!
            exit();
        } elseif ($checkPwd == true) {
            $stmt = $this->connect()->prepare('SELECT * FROM worker WHERE user_id = ? or user_email = ? AND user_pwd = ?;'); // !!!

            if (!$stmt->execute(array($userid, $userid, $pwd))) {
                $stmt = null;
                header("location: medewerker-login.php?error=stmtfailed"); // !!!
                exit();
            }

            if ($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: medewerker-login.php?error=usernotfound"); // !!!
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["id"] = $user[0]["id"]; // !!!
            $_SESSION["user_id"] = $user[0]["user_id"]; // !!!

            $stmt = null;
        }
    }
}

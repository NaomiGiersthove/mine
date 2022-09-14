<?php
class LoginChecker extends LoginDatabase {
    private $userid;
    private $pwd;

    public function __construct($userid, $pwd) {
        $this->userid = $userid;
        $this->pwd = $pwd;
    }

    public function loginUser() {
        if ($this->emptyInput() == false) {
            // echo "Empty input!";
            header("location: medewerker-login.php?error=emptyinput"); // !!!
            exit();
        }

        // als er geen error komt. Wordt de user ingelogd met de gegevens bij de database. linked to login-database.php
        $this->getUser($this->userid, $this->pwd);
    }

    //checks als er niks gevuld is.
    private function emptyInput() {
        $result;
        if (empty($this->userid) || empty($this->pwd)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}

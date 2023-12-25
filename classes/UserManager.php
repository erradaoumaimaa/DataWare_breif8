<?php
require_once "../classes/User.php";
class UserManager
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function authentUser($email, $password)
{
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->database->getConnection()->prepare($query);

    if ($stmt) {
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();  // Close the cursor to allow for next queries

        if ($user && password_verify($password, $user['password'])) {
            return new User($user['id_user'], $user['username'], $user['email'], $user['role']);
        }
    }

    return null;
}

}

?>

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
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($user && password_verify($password, $user['password'])) {
                return new User($user['id_user'], $user['username'], $user['email'], $user['role'], $user['imagePath']);
            }
        }

        return null;
    }

    public function signUp($username, $email, $password, $profilePicture)
{
    // Validate and sanitize user inputs
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if email is already registered
    $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
    $checkEmailStmt = $this->database->getConnection()->prepare($checkEmailQuery);
    $checkEmailStmt->bindValue(":email", $email, PDO::PARAM_STR);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->fetch(PDO::FETCH_ASSOC);
    $checkEmailStmt->closeCursor();

    if ($checkEmailResult) {
        return "This email is already registered.";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $img = "default.jpg";

    if (is_array($profilePicture) && $profilePicture['error'] === UPLOAD_ERR_OK) {
        $targetDirectory = __DIR__ . "/../upload/";
        $targetPath = $targetDirectory . basename($profilePicture['name']);

        // Create the "upload" directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Now move the uploaded file
        if (move_uploaded_file($profilePicture['tmp_name'], $targetPath)) {
            // File uploaded successfully
            $img = "upload/" . basename($profilePicture['name']);
        } else {
            // Error uploading file
            return "Sorry, there was a problem uploading your file.";
        }
    }

    // Use a database transaction for better data integrity
    try {
        $this->database->getConnection()->beginTransaction();

        $query = "INSERT INTO users (username, password, email, image_url, role) VALUES (:username, :password, :email, :img, 'user')";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindValue(":username", $username, PDO::PARAM_STR);
            $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":img", $img, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();

            // Commit the transaction if everything is successful
            $this->database->getConnection()->commit();

            return null; // Success
        } else {
            return "Error: Unable to prepare the statement.";
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $this->database->getConnection()->rollBack();
        return "Error: " . $e->getMessage() . " SQL: " . $query;
    }
}

}

?>

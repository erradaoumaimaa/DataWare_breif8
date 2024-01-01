<?php

require_once "User.php";
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
        $targetDirectory = realpath(__DIR__ . "/../upload/") . "/";
        // Debug statement to check if this block is executed
        echo "File upload block executed<br>";

        // Debug statement to check the values
        echo "Profile Picture Array: ";
        print_r($profilePicture);
        echo "<br>";

        // Use FileUploadHelper to handle file upload
        $uploadResult = FileUploadHelper::uploadFile($profilePicture, $targetDirectory);

        if (is_string($uploadResult)) {
            // Error uploading file
            return $uploadResult;
        } else {
            // Debug statement to check the uploaded result
            echo "File uploaded successfully: $uploadResult<br>";
            $img = $uploadResult;
        }
    }

    // Debug statement to check the final value of $img
    echo "Final value of \$img: $img<br>";

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
public function getUsersExceptProductOwner($productOwnerId)
    {
        $query = "SELECT * FROM users WHERE id != :productOwnerId AND role != 'po'";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindValue(":productOwnerId", $productOwnerId, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $users;
        }

        return [];
    }

    public function changeUserRole($userId)
    {
        $query = "UPDATE users SET role = CASE WHEN role = 'user' THEN 'sm' ELSE 'user' END WHERE id = :userId";
        $stmt = $this->database->getConnection()->prepare($query);
    
        if ($stmt) {
            $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            return 'Role changed successfully';
        } else {
            return 'Error: Unable to prepare the statement.';
        }
    }
    
}

?>

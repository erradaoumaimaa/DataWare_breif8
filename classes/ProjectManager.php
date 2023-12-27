<?php

require_once "Database.php";
require_once "Project.php";
require_once "User.php"; 
class ProjectManager
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function createProject($name, $description, $endDate, $status, $productOwnerId, $scrumMasterEmail)
    {
        // Get the Scrum Master's user ID based on their email
        $scrumMasterId = $this->getScrumMasterIdByEmail($scrumMasterEmail);

        if ($scrumMasterId === null) {
            return "Error: Scrum Master not found.";
        }

        // Use a database transaction for better data integrity
        try {
            $this->database->getConnection()->beginTransaction();

            $query = "INSERT INTO projects (name, description, date_start, date_end, status, product_owner_id, scrum_master_id) 
                        VALUES (:name, :description, NOW(), :endDate, :status, :productOwnerId, :scrumMasterId)";
            $stmt = $this->database->getConnection()->prepare($query);

            if ($stmt) {
                $stmt->bindValue(":name", $name, PDO::PARAM_STR);
                $stmt->bindValue(":description", $description, PDO::PARAM_STR);
                $stmt->bindValue(":endDate", $endDate, PDO::PARAM_STR);
                $stmt->bindValue(":status", $status, PDO::PARAM_STR);
                $stmt->bindValue(":productOwnerId", $productOwnerId, PDO::PARAM_INT);
                $stmt->bindValue(":scrumMasterId", $scrumMasterId, PDO::PARAM_INT);
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
            return "Error: " . $e->getMessage();
        }
    }

    private function getScrumMasterIdByEmail($scrumMasterEmail)
    {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindValue(":email", $scrumMasterEmail, PDO::PARAM_STR);
            $stmt->execute();
            $scrumMasterId = $stmt->fetchColumn();
            $stmt->closeCursor();

            return $scrumMasterId;
        }

        return null;
    }
    public function getScrumMasters()
    {
        $query = "SELECT email FROM users WHERE role = 'scrum_master'";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->execute();
            $scrumMasters = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $scrumMasters;
        }

        return [];
    }
    public function getProjectsForProductOwner($productOwnerId)
    {
        $query = "SELECT p.*, u.email as scrum_master FROM projects p
                  INNER JOIN users u ON p.scrum_master_id = u.id
                  WHERE p.product_owner_id = :productOwnerId";
        $stmt = $this->database->getConnection()->prepare($query);
    
        if ($stmt) {
            $stmt->bindValue(":productOwnerId", $productOwnerId, PDO::PARAM_INT);
            $stmt->execute();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
            return $projects;
        }
    
        return [];
    }
}

?>

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
        try {
            $query = "SELECT id FROM users WHERE LOWER(email) = LOWER(:email)";
            $stmt = $this->database->getConnection()->prepare($query);
    
            if ($stmt) {
                $trimmedEmail = trim($scrumMasterEmail);
                $stmt->bindValue(":email", $trimmedEmail, PDO::PARAM_STR);
                $stmt->execute();
    
                // Obtenez l'ID du Scrum Master
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($result && isset($result['id'])) {
                    $scrumMasterId = $result['id'];
                    $stmt->closeCursor();
                    return $scrumMasterId;
                } else {
                    // Ajoutez ces lignes pour déboguer
                    error_log("Scrum Master not found for email: " . $trimmedEmail);
                    return null;
                }
            } else {
                // Ajoutez ces lignes pour déboguer
                error_log("Unable to prepare the statement for email: " . $scrumMasterEmail);
                return null;
            }
        } catch (Exception $e) {
            // Ajoutez ces lignes pour déboguer
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }
    
    


    public function getScrumMasters()
    {
        $query = "SELECT email FROM users WHERE role = 'sm'";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->execute();
            $scrumMasters = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $scrumMasters;
        }

        return [];
    }

    public function getProjectById($projectId)
    {
        $query = "SELECT * FROM projects WHERE id = :projectId";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindValue(":projectId", $projectId, PDO::PARAM_INT);
            $stmt->execute();
            $projectData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $projectData;
        }

        return null;
    }

    public function getProjectsForProductOwner($productOwnerId)
    {
        $query = "SELECT p.*, u.email as sm FROM projects p
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

    private function scrumMasterExists($scrumMasterId)
    {
        $query = "SELECT COUNT(*) FROM users WHERE id = :scrumMasterId";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindValue(":scrumMasterId", $scrumMasterId, PDO::PARAM_INT);
            $stmt->execute();

            // Debug information
            $queryString = $stmt->queryString;
            $values = [':scrumMasterId' => $scrumMasterId];
            error_log("Query: $queryString, Values: " . json_encode($values));

            $count = $stmt->fetchColumn();
            $stmt->closeCursor();

            return ($count > 0);
        }

        return false;
    }

    public function updateProject($projectId, $name, $description, $endDate, $status, $scrumMasterId)
    {
        try {
            $this->database->getConnection()->beginTransaction();
    
            $query = "UPDATE projects 
                      SET name = :name, 
                          description = :description, 
                          date_end = :endDate, 
                          status = :status, 
                          scrum_master_id = :scrumMasterId 
                      WHERE id = :projectId";
    
            $stmt = $this->database->getConnection()->prepare($query);
    
            if ($stmt) {
                $stmt->bindValue(":name", $name, PDO::PARAM_STR);
                $stmt->bindValue(":description", $description, PDO::PARAM_STR);
                $stmt->bindValue(":endDate", $endDate, PDO::PARAM_STR);
                $stmt->bindValue(":status", $status, PDO::PARAM_STR);
                $stmt->bindValue(":scrumMasterId", $scrumMasterId, PDO::PARAM_INT);
                $stmt->bindValue(":projectId", $projectId, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
    
                $this->database->getConnection()->commit();
    
                return null; // Success
            } else {
                return "Error: Unable to prepare the statement.";
            }
        } catch (Exception $e) {
            $this->database->getConnection()->rollBack();
            return "Error: " . $e->getMessage();
        }
    }
    


public function deleteProject($projectId)
{
    try {
        // Use a database transaction for better data integrity
        $this->database->getConnection()->beginTransaction();

        // Delete the project
        $query = "DELETE FROM projects WHERE id = :projectId";
        $stmt = $this->database->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindValue(":projectId", $projectId, PDO::PARAM_INT);
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


}

?>

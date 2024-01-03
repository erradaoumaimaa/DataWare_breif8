<?php
require_once "../../classes/Team.php";
class TeamManager {
    private $database;

    public function __construct($database) {
        $this->database = $database;
        $this->database->connect();
    }

    public function getTeams() {
        $teams = [];

        try {
            // $query = "SELECT teams.*, users.username AS scrum_master_name FROM teams LEFT JOIN users ON teams.scrum_master_id = users.id";
            $query = "SELECT teams.*, projects.name AS project_name, users.username AS scrum_master_name 
            FROM teams 
            LEFT JOIN users ON teams.scrum_master_id = users.id
            LEFT JOIN projects ON teams.id = projects.team_id";
  

            $stmt = $this->database->getConnection()->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $team = new Team($row['id'], $row['name'], $row['created_at'], $row['description'], $row['scrum_master_name']);
                $teams[] = $team;
            }
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des équipes : " . $e->getMessage());
        } finally {
            $this->database->disconnect();
        }

        return $teams;
    }


  
    public function createTeam($name, $description, $scrumMasterId)
    {
        try {
            $this->database->getConnection()->beginTransaction();

        

            $query = "INSERT INTO teams (name, description, scrum_master_id, created_at) 
                      VALUES (:name, :description, :scrumMasterId, NOW())";
            $stmt = $this->database->getConnection()->prepare($query);

            if ($stmt) {
                $stmt->bindValue(":name", $name, PDO::PARAM_STR);
                $stmt->bindValue(":description", $description, PDO::PARAM_STR);
                $stmt->bindValue(":scrumMasterId", $scrumMasterId, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();

                $this->database->getConnection()->commit();

                return null; // Succès
            } else {
                return "Erreur : Impossible de préparer l'instruction.";
            }
        } catch (Exception $e) {
            $this->database->getConnection()->rollBack();
            return "Erreur : " . $e->getMessage();
        }
    }
    public function deleteTeam($teamId)
    {
        try {
            $this->database->getConnection()->beginTransaction();

         
            $query = "DELETE FROM teams WHERE id = :teamId";
            $stmt = $this->database->getConnection()->prepare($query);

            if ($stmt) {
                $stmt->bindValue(":teamId", $teamId, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();

                $this->database->getConnection()->commit();

                return null; // Succès
            } else {
                return "Erreur : Impossible de préparer l'instruction.";
            }
        } catch (Exception $e) {
            $this->database->getConnection()->rollBack();
            return "Erreur : " . $e->getMessage();
        }
    }
    public function updateTeam($teamId, $name, $description, $scrumMasterId)
    {
        try {
            $this->database->getConnection()->beginTransaction();


            $query = "UPDATE teams 
                      SET name = :name, 
                          description = :description, 
                          scrum_master_id = :scrumMasterId 
                      WHERE id = :teamId";
            $stmt = $this->database->getConnection()->prepare($query);

            if ($stmt) {
                $stmt->bindValue(":name", $name, PDO::PARAM_STR);
                $stmt->bindValue(":description", $description, PDO::PARAM_STR);
                $stmt->bindValue(":scrumMasterId", $scrumMasterId, PDO::PARAM_INT);
                $stmt->bindValue(":teamId", $teamId, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();

                $this->database->getConnection()->commit();

                return null; // Succès
            } else {
                return "Erreur : Impossible de préparer l'instruction.";
            }
        } catch (Exception $e) {
            $this->database->getConnection()->rollBack();
            return "Erreur : " . $e->getMessage();
        }
    }
    public function getTeamById($teamId)
    {
        $query = "SELECT * FROM teams WHERE id = :teamId";
        $stmt = $this->database->getConnection()->prepare($query);
    
        if ($stmt) {
            $stmt->bindValue(":teamId", $teamId, PDO::PARAM_INT);
            $stmt->execute();
    
            $teamData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
            // Afficher le contenu pour le débogage
            var_dump($teamData);
    
            if ($teamData) {
                return new Team($teamData['id'], $teamData['name'], $teamData['created_at'], $teamData['description'], $teamData['scrum_master_id']);
            }
        }
    
        return null;
    }
    
}
?>

<?php
require_once "User.php";
require_once "Team.php";
class ScrumMaster extends User {
    private $db;

    public function __construct($id, $username, $email, $role, $imageUrl, Database $db) {
        parent::__construct($id, $username, $email, $role, $imageUrl, $db);
        $this->db = $db;
    }

    public function getTeams() {
        $teams = [];

        try {
            $query = "SELECT teams.*, users.username AS scrum_master_name
                      FROM teams
                      LEFT JOIN users ON teams.scrum_master_id = users.id";

            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $team = new Team($row['id'], $row['name'], $row['created_at'], $row['description'], $row['scrum_master_name']);
                $teams[] = $team;
            }
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des équipes : " . $e->getMessage());
        } finally {
            $this->db->disconnect();
        }

        return $teams;
    }
}
?>
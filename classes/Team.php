<?php
class Team{
    private $id;
    private $name;
    private $createdAt;
    private $description;
    private $scrumMasterId;

 
    public function __construct($id, $name, $createdAt, $description, $scrumMasterId) {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->description = $description;
        $this->scrumMasterId = $scrumMasterId;
    }
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getDescription() {
        return $this->description;
    }

    public function  getScrumMasterId() {
        return $this->scrumMasterId;
    }
 
    public function setId($id) {
        $this->id = $id;
    }


  
}

?>
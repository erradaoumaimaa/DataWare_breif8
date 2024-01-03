<?php
class Project
{
    private $id;
    private $name;
    private $description;
    private $endDate;
    private $status;
    private $productOwnerId;
    private $scrumMasterId;

    public function __construct($id, $name, $description, $endDate, $status, $productOwnerId, $scrumMasterId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->productOwnerId = $productOwnerId;
        $this->scrumMasterId = $scrumMasterId;
    }
    // getters
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getProductOwnerId()
    {
        return $this->productOwnerId;
    }

    public function getScrumMasterId()
    {
        return $this->scrumMasterId;
    }
}
?>

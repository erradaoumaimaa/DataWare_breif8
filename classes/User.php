<?php

class User
{
    private $id;
    private $username;
    private $email;
    private $role;
    private $imagePath;
    public function __construct($id, $username, $email, $role , $imagePath)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->imagePath = $imagePath;
    }

    // getters 
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRole()
    {
        return $this->role;
    }
    public function getImagePath()
    {
        return $this->imagePath;
    }
    
    
}

?>

<?php

class User
{
    private $id;
    private $username;
    private $email;
    private $role;

    public function __construct($id, $username, $email, $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
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

    
}

?>

<?php
session_start();
class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Database();
    }

    function getData($email)
    {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $this->conn->query($query);
            $this->conn->bind(':email', $email);
            return $this->conn->single();
        } catch (PDOException $e) {
            echo '<script> alert("' . $e->getMessage() . '")</script>';
            return null;
        }
    }
    public function insertData($username, $email, $password)
    {
        try {
            $query = "INSERT INTO users (username,email, password) VALUES (:username, :email,:password)";
            $this->conn->query($query);

            $this->conn->bind(':username', $username);
            $this->conn->bind(':email', $email);
            $this->conn->bind(':password', $password);

            $this->conn->execute();
        } catch (PDOException $e) {
            echo '<script> alert("' . $e->getMessage() . '")</script>';
        }
    }

    public function storeSession($email)
    {
        $_SESSION['email'] = $email;
    }

    public function modifyData($id, $newData)
    {
        try {
            $query = "UPDATE users SET username = :username WHERE email = :id";
            $this->conn->query($query);

            $this->conn->bind(':id', $id);
            $this->conn->bind(':usename', $newData['usename']);
            $this->conn->execute();
            header("Location: index.php");
        } catch (PDOException $e) {
            echo '<script> alert("' . $e->getMessage() . '")</script>';
        }
    }

    public function login($email, $password)
    {
        $this->conn->bind(':email', $email);

        $row = $this->conn->single();

        if ($row && property_exists($row, 'password')) {
            $stored_password = base64_decode($row->password);

            if ($password === $stored_password) {
                return $row;
            }
        }

        return false;

    }

    public function findUserByEmail($email)
    {
        $this->conn->query('SELECT * FROM users WHERE email = :email');
        $this->conn->bind(':email', $email);

        $this->conn->execute();

        if ($this->conn->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

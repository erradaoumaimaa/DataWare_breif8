<?php

class Users extends Controller
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $data = [
            'title' => 'LogIn'
        ];
        $this->view('users/index', $data);
    }
    public function signupPage()
    {

        $this->view('users/signup');
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            if ($this->userModel->findUserByEmail($data['email'])) {

                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    echo '<script>alert("Incorrect Password")</script>';

                    $this->view('users/index', $data);
                }

            } else {
                echo '<script>alert("No user found")</script>';
            }
        } else {
            $data = [
                'email' => '',
                'password' => '',
            ];

            $this->view('users/index', $data);
        }
    }
    public function signup(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'fname' => trim($_POST['fname']),
                'lname' => trim($_POST['lname']),
                'email' => trim($_POST['email']),
                'service' => trim($_POST['service']),
                'tel' => trim($_POST['tel']),
                'password' =>  base64_encode(trim($_POST['password'])),
            ];

            $this->userModel->insertData($data['fname'], $data['lname'], $data['email'], $data['service'], $data['tel'], $data['password']);
            $loggedInUser = $this->userModel->getData($data['email']);

            $this->createUserSession($loggedInUser);

        }
    }
    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $data = $user;
        $this->view('users/dashboard',$data);
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/index');
    }

    public function dashboardPage(){
        $this->view('users/dashboard');
    }
}

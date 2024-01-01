<?php
require_once "../config/config.php";
require_once "../classes/UserManager.php";
require_once "../classes/FileUploadHelper.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Use the FileUploadHelper to handle file upload
    $img = FileUploadHelper::uploadFile($_FILES["profilePicture"], __DIR__ . "/../upload/");

    $userManager = new UserManager($database);

    $signUpResult = $userManager->signUp($username, $email, $password, $img);

    if ($signUpResult === null) {
        // Redirection after successful registration
        header("Location: index.php");
        exit();
    } else {
        // Display a more detailed error message
        $message = "Registration failed. Reason: " . $signUpResult;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Add Tailwind CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <title>Registration Form</title>
</head>

<body class="bg-cover bg-center h-screen flex items-center justify-center"
  style="background-image: url('../public/img/image1.jpg');">

  <div class="bg-white p-8 rounded w-96 shadow-md max-w-md rounded-2xl">

    <h2 class="text-2xl text-center mb-6">Sign Up</h2>

    <form action="signup.php" method="POST" enctype="multipart/form-data">
      <div class="mt-4">
        <input type="text" id="username" name="username" placeholder="Enter your username" class="mt-1 p-2 w-full border rounded-md">
      </div>
      <div class="mt-4">
        <input type="email" id="email" name="email" placeholder="Enter your email" class="mt-1 p-2 w-full border rounded-md">
      </div>
      <div class="mt-4">
        <input type="password" id="password" name="password" placeholder="Enter your password" class="mt-1 p-2 w-full border rounded-md">
      </div>
      <div class="mt-4">
        <input type="file" id="profilePicture" name="profilePicture" accept="image/*" class="mt-1 p-2 w-full border rounded-md">
      </div>
      <div class="mt-6">
        <button type="submit"
          class="w-full px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
          Register Now
        </button>
        <p class="mt-4 text-gray-600 text-xs text-center"> have an account? <a href="./index.php"
                    class="text-blue-500 hover:underline">Sign in here</a>.</p>
        <p class="my-4 text-red-600 text-xs text-center">
          <?php
          if (!empty($message)) {
            echo $message;
          }
          ?>
        </p>
      </div>
    </form>
  </div>

</body>

</html>

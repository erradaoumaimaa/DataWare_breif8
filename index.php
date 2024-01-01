<?php
require_once "./config/config.php";
require_once "./classes/UserManager.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $motDePasse = $_POST['password'];

    $userManager = new UserManager($database);
    $auth = $userManager->authentUser($email, $motDePasse);

    if ($auth) {
        session_start();
        $_SESSION['id'] = $auth->getId();

        switch ($auth->getRole()) {
            case 'user':
                header("Location: ../pages/user/index.php");
                exit();
            case 'po':
                header("Location: ../pages/product_owner/index.php");
                exit();
            case 'sm':
                header("Location:../pages/scrum_master/index.php");
                exit();
        }
    } else {
        $message = "Mot de passe incorrect ou utilisateur non trouvé.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--CDN du Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!--CDN du JS -->
    <script src="./js/main.js" defer></script>
    <title>Sign In</title>
</head>

<body class="bg-cover bg-center h-screen flex items-center justify-center"
                    style="background-image: url('../public/img/image1.jpg');">

    <div class="bg-white p-8 rounded w-96 shadow-md max-w-md rounded-2xl">

        <h2 class="text-2xl text-center mb-6">Sign In</h2>
        <?php
        if (!empty($message)) {
            echo "<p class='my-4 text-red-600 text-xs text-center'>$message</p>";
        }
        ?>
        <form name="signInForm" action="" method="POST" onsubmit="return validateForm();">
            <!--Email input-->
            <div class="mb-4">
                <input type="email" id="email" name="email" placeholder="Email"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 drop-shadow-lg"
                    required>
            </div>
            <!--Password input-->
            <div class="mb-6">
                <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" placeholder="Password"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 drop-shadow-lg"
                    required>
            </div>

            <button type="submit"
            class="w-full px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                Get started
            </button>

            <p class="mt-4 text-gray-600 text-xs text-center">Don't have an account? <a href="./signup.php"
                    class="text-blue-500 hover:underline">Sign up here</a>.</p>
        </form>

    </div>

</body>

</html>
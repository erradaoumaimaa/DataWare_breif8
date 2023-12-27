<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Product Owner</title>
</head>
<body class="bg-gray-100 ">
<?php
require_once "../../includes/headers/header_product_owner.php"
?>
    <h1>hi Product Owner</h1>
<div class="container mx-auto mt-8">
    <div class="mb-4">
    <h1 class="font-serif text-3xl font-bold underline decoration-gray-400"> List of Members :</h1>
    <div class="flex justify-end">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow">
                <div class="flex flex-col items-center py-6">
                    
                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="../../upload/najoua.jfif" alt="User Image">
                    <h5 class="mb-1 text-xl font-medium text-gray-900"></h5>
                    <span class="text-sm text-gray-500"></span>
                    
                   
                    <span class="inline-block px-2 py-1 text-xs font-semibold text-white" style="rounded-full mt-2">
                        Role: 
                    </span>
                    <!-- Add more fields as needed -->

                    <div class="flex mt-4">
                        <!-- Add a form to change the user's role -->
                        <form action="./change_role.php" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $userData['id']; ?>">
                            <select name="new_role" class="mr-2 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                <option value="user">User</option>
                                <option value="scrum_master">Scrum Master</option>
                            </select>
                           
                            <button type="submit" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:focus:ring-yellow-900">Update</button>
                        </form>
                    </div>
                </div>
            </div>
           
        
       
    </div>
</div>

</body>

</html>

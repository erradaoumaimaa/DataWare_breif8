<?php

class FileUploadHelper
{
    public static function uploadFile($file, $targetDirectory)
    {
        $img = "default.jpg"; // Default image

        // Check if a file was uploaded
        if ($file["name"]) {
            // Specify the upload directory
            $targetPath = $targetDirectory . basename($file["name"]);

            // Ensure the directory exists, create it if necessary
            if (!file_exists($targetDirectory)) {
                mkdir($targetDirectory, 0755, true);
            }

            // Move the uploaded file to a permanent location
            if (move_uploaded_file($file["tmp_name"], $targetPath)) {
                // File uploaded successfully
                $img = "upload/" . basename($file["name"]);
            } else {
                // Error uploading file
                echo "Sorry, there was a problem uploading your file.";
            }
        }

        return $img;
    }
}

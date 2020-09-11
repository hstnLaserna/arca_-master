<?php
// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if file was uploaded without errors
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        //$filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];
        $filename = substr(password_hash(rand(100000,999999), PASSWORD_BCRYPT),12,28);

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 15MB maximum
        $maxsize = 15 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Verify MIME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("/" . $filename)){
                echo $filename . " already exists.";
            } else{
                move_uploaded_file($_FILES["photo"]["tmp_name"], "/" . $filename);
                $photo_upload = "$filename.$ext";
            }
        } else{
            echo "Error: There was a problem uploading your file. Please try again.";
        }
    } else{
        echo "Error: " . $_FILES["photo"]["error"];
    }
}

?>

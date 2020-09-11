
<?php
include("../backend/conn.php");

//if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0)
    {
        if(isset($_POST['entity_type'])) {
            $entity_type = $mysqli->real_escape_string($_POST['entity_type']);
            
            switch ($entity_type) {
                case 'member':
                    $osca_id = $mysqli->real_escape_string($_POST['entity_key']);
                    $query_validation_exists = "SELECT * FROM `member` WHERE `osca_id` = '$osca_id';";
                    $target_dir = "../resources/members/";
                    break;
                case 'admin':
                    $user_name = $mysqli->real_escape_string($_POST['entity_key']);
                    $query_validation_exists = "SELECT * FROM `admin` WHERE `user_name` = '$user_name';";
                    $target_dir = "../resources/avatars/";
                    break;
                case 'company':
                    $company_tin = $mysqli->real_escape_string($_POST['entity_key']);
                    $query_validation_exists = "SELECT * FROM `company` WHERE `company_tin` = '$company_tin';";
                    $target_dir = "../resources/logo/";
                    break;
                
                default:
                    $target_dir = "../resources/logo/images";
                    break;
            }
            echo "<script>alert('$query_validation_exists')</script>";
            
            $result = $mysqli->query($query_validation_exists);
            $row_count = mysqli_num_rows($result);
            if($row_count == 1) { // proceed with upload
                $filename = substr(MD5(rand(100000,999999)),0,16);
                $target_file = $target_dir . basename($filename);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION));
                $photo_filename = $filename . "." . $imageFileType;
                
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["photo"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                    }
                }
                
                if (file_exists($target_file)) {
                    $uploadOk = 0;
                }
                
                $maxsize = 15 * 1024 * 1024;
                if ($_FILES["photo"]["size"] > $maxsize) {
                    $uploadOk = 0;
                }
                
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                    $uploadOk = 0;
                }
                
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                } else {
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file . "." . $imageFileType )) {
                        switch ($entity_type) {
                            case 'member':
                                $query = "CALL `edit_member_picture`('$osca_id', '$photo_filename', @msg)";
                                $redirect_location = "window.location.replace('../frontend/member_profile.php?member_id=$osca_id')";
                                break;
                            case 'admin':
                                $query = "CALL `edit_admin_picture`('$user_name', '$photo_filename', @msg)";
                                $redirect_location = "window.location.replace('../frontend/user_profile.php?user=$user_name')";
                                break;
                            case 'company':
                                $query = "CALL `edit_company_logo`('$company_tin', '$photo_filename', @msg)";
                                $redirect_location = "window.location.replace('../frontend/company_profile.php?company_tin=$company_tin')";
                                break;
                            default:
                                $target_dir = "../resources/logo/images";
                                break;
                        }
                        
                        $result = $mysqli->query($query);
                        ?>
                            <script>
                                <?php echo $redirect_location; ?>
                            </script>
                        <?php
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            } else {
                $entity_key = $mysqli->real_escape_string($_POST['entity_key']);
                echo "The $entity_type '$entity_key' does not exist";
            }
            
        } else  {
            echo "False";
        }
    }//   else{
    //    echo "Error: " . $_FILES["photo"]["error"];
    //}
}

?>

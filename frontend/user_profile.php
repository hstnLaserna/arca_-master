<?php
  include('../backend/conn.php');
  $db = mysqli_connect($host,$user,$pass,$schema);

    if(isset($logged_user_id))
    {
        $query = "SELECT * FROM `admin` WHERE `id` = '$logged_user_id'";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count == 0) { echo "ID Does not exist";} else
        {
            if($row_count > 1) { echo "ID returns more than 1 record";} else{}
            ?>
            <div class="container">


            <?php
            while($row = mysqli_fetch_array($result))
            {
            ?>    <td><img src="../resources/<?php if($row['avatar'] == "null"){echo "images/unknown_m_f.png";}else{echo "avatars/".$row['avatar'];}?>" class="avatar rounded" alt="<?php echo $row['user_name'] ?>"></td>
                <div><?php echo $row['id'] ?></div>
                <div><?php echo $row['user_name'] ?></div>
                <div><?php echo $row['first_name'] ?></div>
                <div><?php echo $row['middle_name'] ?></div>
                <div><?php echo $row['last_name'] ?></div>
                <div><?php echo $row['birth_date'] ?></div>
                <div><image class="table_icon" src="../resources/images/<?php if($row['sex'] == 'f'||$row['sex'] == 'F'){echo "female";}else{echo "male";}?>.png">
            <?php
            }
            echo "</div>";
        }
        mysqli_close($mysqli);    // Closing Connection
    } else
    {
        echo "Invalid id";
    }
?>
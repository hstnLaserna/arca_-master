<?php
  include('../backend/conn.php');

  $items_per_page = 10;

  
  
  $query = "SELECT * FROM `admin`";
  $result = $mysqli->query($query);
  $row_count_all = mysqli_num_rows($result);

  // determine page_count
  $page_count = 0;
  $page_count = (int)ceil($row_count_all / $items_per_page);

  if($page > $page_count) {
      $page = 1;
  }
  
  $offset = ($page - 1) * $items_per_page;

  $query = "SELECT * FROM `admin` `a`
          ORDER BY `a`.`user_name` ASC, `a`.`last_name` ASC, `a`.`first_name` ASC
                        LIMIT $offset,$items_per_page";
  $result = $mysqli->query($query);
  $row_count = mysqli_num_rows($result);
?>
<div class="table-responsive" id ="display_admin">
 <table class="table table-hover users">
    <th>Picture</th>
    <th>Username</th>
    <th>Firstname</th>
    <th>Lastname</th>
    <th>Position</th>
    <th style="text-align: center;">Enabled</th>
    <?php

    while($row = mysqli_fetch_array($result))
    {
      $id = $row['id'];
      $user_name = $row['user_name'];
      $first_name = $row['first_name'];
      $last_name = $row['last_name'];
      $position = ucfirst($row['position']);
      if($row['is_enabled'] == 1){
        $is_active =  "active";
      } else {
        $is_active = "inactive";
      }


      $avatar = '../resources/avatars/'.$row["avatar"];
      if (file_exists($avatar) && $row["avatar"] != null) { 
        // something
      } else {
        $avatar = "../resources/images/unknown_m_f.png"; 
      }
    ?>
      <tr id="admin_<?php echo $user_name; ?>" >
        <td><img src="<?php echo $avatar ?>" alt="<?php echo $user_name?>" class="avatar view-admin"></td>
        <td><?php echo $user_name; ?></td>
        <td><?php echo $first_name; ?></td>
        <td><?php echo $last_name; ?></td>
        <td><?php echo $position; ?></td>
        <td class="isEnabled <?php echo $is_active ?>"></td>
      </tr>
    <?php

    }
      echo "</table>";
      mysqli_close($mysqli);// Closing Connection
    ?>
        <div><?php echo "Record ". ($offset+1)." to ".($row_count+$offset)." of $row_count_all <br>";?></div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $page_count; $i++) {
                    if ($i === $page) { // this is current page
                        ?>
                        <li class="page-item disabled"><a class="page-link"><?php echo $i?></a></li>
                        <?php
                    } else { // show link to other page   
                        ?>
                        <li class="page-item"><a class="page-link" href="../frontend/administrators.php?page=<?php echo $i?>"><?php echo $i?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
</div>

<script>
    $(".page-link").click(function() {
      var page= $(this).closest("li").attr("id");
      location.href='../frontend/administrators.php?page='+page;
//      $("#display_admin").load("../backend/display_members_search.php?page="+page);
  });
</script>
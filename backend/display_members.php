

<?php
    include('../backend/conn.php');

    $page=1;
    if(!empty($_GET['page'])) {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if(false === $page) {
            $page = 1;
        }
    }

    $items_per_page = 10;

    
    
    $query = "SELECT * FROM `member` m
                INNER JOIN `address_jt` ajt ON ajt.`member_id` = m.`id`
                INNER JOIN `address` a ON ajt.`address_id` = a.`id`
                WHERE `a`.`is_active` = 1 GROUP BY m.`osca_id`; ";
    $result = $mysqli->query($query);
    $row_count_all = mysqli_num_rows($result);

    // determine page_count
    $page_count = 0;

    $page_count = (int)ceil($row_count_all / $items_per_page);
    // double check that request page is in range
    if($page > $page_count) {
        $page = 1;
    }
    $offset = ($page - 1) * $items_per_page;

    $query = "SELECT 	`m`.`id`,	`m`.`osca_id`,	`m`.`first_name`,	`m`.`middle_name`,
                `m`.`last_name`,	`m`.`picture`,	`a`.`city`, `a`.`province`
                FROM `member` m
                INNER JOIN `address_jt` ajt ON ajt.`member_id` = m.`id`
                INNER JOIN `address` a ON ajt.`address_id` = a.`id`
                WHERE `a`.`is_active` = 1
                GROUP BY m.`osca_id`
                ORDER BY `m`.`last_name` ASC, `m`.`first_name` ASC 
                LIMIT $offset,$items_per_page";

    
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);
    if($row_count > 0) 
    {
            ?>

        <div class="table-responsive">
            <table class="table table-hover users">
                <th>OSCA ID</th>
                <th>Firstname</th>
                <th>Middlename</th>
                <th>Lastname</th>
                <th>City</th>
                <th>Province</th>
                    <?php
                while($row = mysqli_fetch_array($result))
                {
                    $id = $row['id'];
                    $osca_id = $row['osca_id'];
                    $first_name = $row['first_name'];
                    $middle_name = $row['middle_name'];
                    $last_name = $row['last_name'];
                    $city = $row['city'];
                    $province = $row['province'];
                    
                    ?>
                    <tr class="member-row view-member" id="memNum_<?php echo $id?>">
                        <td><?php echo $osca_id ?></td>
                        <td><?php echo $first_name ?></td>
                        <td><?php echo $middle_name ?></td>
                        <td><?php echo $last_name ?></td>
                        <td><?php echo $city ?></td>
                        <td><?php echo $province ?></td>
                    </tr>
                    <?php
                }
                mysqli_close($mysqli);
                ?>
            </table>

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
                            <li class="page-item"><a class="page-link" href="../frontend/members.php?page=<?php echo $i?>"><?php echo $i?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
        <?php
    } else { }
?>


<div class="table-responsive">
    <table class="table table-hover users">
        <th>Company Name</th>
        <th>Business</th>
        <th>City</th>
        <th>Province</th>

<?php
    include('../backend/conn.php');
    $display_true = false;

    $page=1;
    if(!empty($_GET['page'])) {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if(false === $page) {
            $page = 1;
        }
    }

    $items_per_page = 10;

    $query = "SELECT * FROM `company`;";
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

    $query = "SELECT * FROM
                (SELECT c.id, c.company_name, c.branch, c.company_tin, c.business_type, a.city, a.province
                    FROM `company` c
                    INNER JOIN `address_jt` ajt ON ajt.`company_id` = c.`id`
                    INNER JOIN `address` a ON ajt.`address_id` = a.`id` ) as `t1`
                UNION SELECT * FROM
                (SELECT c.id, c.company_name, c.branch, c.company_tin, c.business_type, '-' city, '-' province
                    FROM `company` c 
                    WHERE c.`id` NOT IN (SELECT `company_id` FROM `address_jt` ajt  WHERE `company_id` IS NOT NULL) ) as `t2`
            ORDER BY `business_type` ASC, `company_name` ASC, `branch` ASC
            LIMIT $offset,$items_per_page";


    
    $result = $mysqli->query($query);
    $row_count = mysqli_num_rows($result);
    if($row_count > 0) 
    {
        $display_true = true;
        while($row = mysqli_fetch_array($result))
        {
            $company_name = $row['company_name'];
            $branch = $row['branch'];
            $company_tin = $row['company_tin'];
            $business_type = $row['business_type'];
            $city = $row['city'];
            $province = $row['province'];
            
            ?>
            <tr class="member-row view-member" id="ct_">
                <td><a href="#" class="view-company" id="ct_<?php echo $company_tin?>"><?php echo $company_name ?> - <?php echo $branch ?> </a></td>
                <td><?php echo $business_type ?></td>
                <td><?php echo $city ?></td>
                <td><?php echo $province ?></td>
            </tr>
            <?php
        }
        mysqli_close($mysqli);
    } else { }
?>


    </table>
</div>

<?php 
 if($display_true){
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
                    <li class="page-item"><a class="page-link" href="../frontend/companies.php?page=<?php echo $i?>"><?php echo $i?></a></li>
                    <?php
                }
            }
            ?>
        </ul>
    </nav>
    <?php 
}
?>

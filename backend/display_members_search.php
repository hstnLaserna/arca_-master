<div class="table-responsive" id="query-rows">
<table class="table table-hover users">
    <th>Picture</th>
    <th>OSCA ID</th>
    <th>Firstname</th>
    <th>Middlename</th>
    <th>Lastname</th>
    <th>City</th>
    <th>Province</th>
<?php
    include('../backend/conn.php');
    $search_true = false;

    $page=1;
    if(!empty($_GET['page'])) {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if(false === $page) {
            $page = 1;
        }
    }

    $items_per_page = 5;

    $offset = ($page - 1) * $items_per_page;

    
    if(($_POST['fname'] == "" && $_POST['mname'] == "" && $_POST['lname'] == "" && $_POST['oid'] == "") == "")
    {
        $search_true = true;
        $where_query = "";
        $where_appended = false;
        $get_next_page ="";
        if(isset($_POST['fname']) && $_POST['fname']!= ""){
            $get_first_name = $_POST['fname'];
            $where_query .= " lower(`first_name`) LIKE lower('%$get_first_name%') ";
            $where_appended = true;
            $get_next_page .= '<input type="hidden" name="fname" value="' . $get_first_name.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="fname" value="">';  }
        if(isset($_POST['mname']) && $_POST['mname']!= ""){
            $get_middle_name = $_POST['mname'];
            if($where_appended){$where_query .= " AND ";}
            $where_query .= " lower(`middle_name`) LIKE lower('%$get_middle_name%') ";
            $where_appended = true;
            $get_next_page .= '<input type="hidden" name="mname" value="' . $get_middle_name.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="mname" value="">';  }
        if(isset($_POST['lname']) && $_POST['lname']!= ""){
            $get_last_name = $_POST['lname'];
            if($where_appended){$where_query .= " AND ";}
            $where_query .= " lower(`last_name`) LIKE lower('%$get_last_name%') ";
            $where_appended = true;
            $get_next_page .= '<input type="hidden" name="lname" value="' . $get_last_name.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="lname" value="">';  }
        if(isset($_POST['oid']) && $_POST['oid']!= ""){
            $get_osca_id = $_POST['oid'];
            if($where_appended){$where_query .= " AND ";}
            $where_query .= " `osca_id` LIKE '%$get_osca_id%' ";
            $get_next_page .= '<input type="hidden" name="oid" value="' . $get_osca_id.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="oid" value="">';  }
        
        $query = "SELECT 	`m`.`id`,	`m`.`osca_id`,	`m`.`first_name`,	`m`.`middle_name`,
            `m`.`last_name`,	`m`.`picture`,	`a`.`city`, `a`.`province`
            FROM `member` m
            INNER JOIN `address_jt` ajt ON ajt.`member_id` = m.`id`
            INNER JOIN `address` a ON ajt.`address_id` = a.`id`
            WHERE $where_query
            GROUP BY `osca_id`";
        
        $result = $mysqli->query($query);
        $row_count_all = mysqli_num_rows($result);

          // determine page_count
            $page_count = 0;
            $page_count = (int)ceil($row_count_all / $items_per_page);

            if($page > $page_count) {
                $page = 1;
            }

            $offset = ($page - 1) * $items_per_page;


        $query = "SELECT 	`m`.`id`,	`m`.`osca_id`,	`m`.`first_name`,	`m`.`middle_name`,
                    `m`.`last_name`,	`m`.`picture`,	`a`.`city`, `a`.`province`
                    FROM `member` m
                    INNER JOIN `address_jt` ajt ON ajt.`member_id` = m.`id`
                    INNER JOIN `address` a ON ajt.`address_id` = a.`id`
                    WHERE $where_query
                    GROUP BY `osca_id`
                    ORDER BY `m`.`last_name` ASC, `m`.`first_name` ASC 
                    LIMIT $offset,$items_per_page";
        
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count > 0) 
        {
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
                <tr class="member-row" id="memNum_<?php echo $id?>">
                    <td class="member-avatar">
                    <img src=<?php $picture = '../resources/members/'.$row["picture"]; if (file_exists($picture) && $row["picture"] != null) { echo '"'.$picture.'"'; } else{ echo '"../resources/images/unknown_m_f.png"'; } ?> class="avatar view-member">
                    </td>
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
        }
    } else {}//invalid inputs
    
?>
    </table>
</div>

<?php
if($search_true){ ?>
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
                        <li class="page-item" id ="<?php echo $i?>"><a class="page-link"><?php echo $i?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
    <?php
}
?>

<form method="post" id="fwd_lookup_form">
    <?php    
        if(isset($get_next_page)){
            echo $get_next_page;
        }
    ?>
</form>

<script>
$(document).ready(function(){

    $('.view-member').click(function () {
        var member_id= $(this).closest("tr").attr("id").replace("memNum_", "");
        $('#modal_display_search').load("../frontend/member_profile_card.php", { member_id: member_id },function(){
            $('#modal_display_search').modal();
        });
    });


    $('#clear').click(function () {
        $('#sr_serial').val('');
        $('#display_nfcread').replaceWith('<div id="display_nfcread"> </div>');
    });

    $(".page-link").click(function() {
        var page= $(this).closest("li").attr("id");
        $("#display_search").load("../backend/display_members_search.php?page="+page, $("#fwd_lookup_form").serializeArray());
    });

    { // Sort thru Table headers
        $('th').click(function(){
            

        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc){rows = rows.reverse()}
        for (var i = 0; i < rows.length; i++){table.append(rows[i])}
        });
        function comparer(index) {
            return function(a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }
        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
    }
});
</script>
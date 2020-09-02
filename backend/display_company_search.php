<div class="table-responsive" id="query-rows">
    <table class="table table-hover users">
        <th>Company Name</th>
        <th>Branch</th>
        <th>Business</th>
        <th>City</th>
        <th>Province</th>
<?php
    include('../backend/conn.php');

    $page=1;
    $search_true = false;
    if(!empty($_GET['page'])) {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if(false === $page) {
            $page = 1;
        }
    }

    $items_per_page = 10;

    $offset = ($page - 1) * $items_per_page;

    
    if(($_POST['company_name'] == "" && $_POST['branch'] == "" && $_POST['company_tin'] == "" && $_POST['business_type'] == "") == "")
    {
        $search_true = true;
        $where_query = "";
        $where_appended = false;
        $get_next_page ="";
        if(isset($_POST['company_name']) && $_POST['company_name']!= ""){
            $get_company_name = $_POST['company_name'];
            $where_query .= " lower(`company_name`) LIKE lower('%$get_company_name%') ";
            $where_appended = true;
            $get_next_page .= '<input type="hidden" name="company_name" value="' . $get_company_name . '">'; 
        }else {$get_next_page .= '<input type="hidden" name="company_name" value="">';  }
        if(isset($_POST['branch']) && $_POST['branch']!= ""){
            $get_branch = $_POST['branch'];
            if($where_appended){$where_query .= " AND ";}
            $where_query .= " lower(`branch`) LIKE lower('%$get_branch%') ";
            $where_appended = true;
            $get_next_page .= '<input type="hidden" name="branch" value="' . $get_branch.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="branch" value="">';  }
        if(isset($_POST['company_tin']) && $_POST['company_tin']!= ""){
            $get_company_tin = $_POST['company_tin'];
            if($where_appended){$where_query .= " AND ";}
            $where_query .= " `company_tin` LIKE '%$get_company_tin%' ";
            $where_appended = true;
            $get_next_page .= '<input type="hidden" name="company_tin" value="' . $get_company_tin.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="company_tin" value="">';  }
        if(isset($_POST['business_type']) && $_POST['business_type']!= ""){
            $get_business_type = $_POST['business_type'];
            if($where_appended){$where_query .= " AND ";}
            $where_query .= " `business_type` LIKE '%$get_business_type%' ";
            $get_next_page .= '<input type="hidden" name="business_type" value="' . $get_business_type.'">'; 
        }else {$get_next_page .= '<input type="hidden" name="business_type" value="">';  }
        
        $query = "SELECT *
            FROM `company`
            WHERE $where_query";
        
        $result = $mysqli->query($query);
        $row_count_all = mysqli_num_rows($result);

          // determine page_count
            $page_count = 0;
            $page_count = (int)ceil($row_count_all / $items_per_page);

            if($page > $page_count) {
                $page = 1;
            }

            $offset = ($page - 1) * $items_per_page;


        $query = "SELECT * FROM
                    (SELECT c.id, c.company_name, c.branch, c.company_tin, c.business_type, a.city, a.province
                        FROM `company` c
                        INNER JOIN `address_jt` ajt ON ajt.`company_id` = c.`id`
                        INNER JOIN `address` a ON ajt.`address_id` = a.`id`
                        WHERE $where_query
                        GROUP BY c.`company_tin`) as `t1`
                    UNION SELECT * FROM
                    (SELECT c.id, c.company_name, c.branch, c.company_tin, c.business_type, '-' city, '-' province
                        FROM `company` c 
                        WHERE $where_query AND c.`id` NOT IN (SELECT `company_id` FROM `address_jt` ajt  WHERE `company_id` IS NOT NULL) ) as `t2`
                    ORDER BY `business_type` ASC, `company_name` ASC, `branch` ASC
                    LIMIT $offset,$items_per_page";
        $result = $mysqli->query($query);
        $row_count = mysqli_num_rows($result);
        if($row_count > 0) 
        {
                    while($row = mysqli_fetch_array($result))
                    {
                        $company_name = $row['company_name'];
                        $branch = $row['branch'];
                        $company_tin = $row['company_tin'];
                        $business_type = $row['business_type'];
                        $city = $row['city'];
                        $province = $row['province'];
                        
                        ?>
                        <tr class="member-row view-member" id="ct_<?php echo $company_tin?>">
                        <td><a href="#" class="view-company" id="ct_<?php echo $company_tin?>"><?php echo $company_name ?> - <?php echo $branch ?> </a></td>
                            <td><?php echo $business_type ?></td>
                            <td><?php echo $city ?></td>
                            <td><?php echo $province ?></td>
                        </tr>
                        <?php
                    }
            mysqli_close($mysqli);
        }
    } else //invalid inputs
    {
    }
    
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

    
    
    $('.view-company').click(function () {
      var company_tin= $(this).attr("id").replace("ct_", "");
        
      var url = '../frontend/company_profile.php';
      var form = $(   '<form action="' + url + '" method="get">' +
                          '<input type="hidden" name="company_tin" value="' + company_tin + '" />' +
                      '</form>');
      $('._dfg987').append(form);
      form.submit();
    });


    $('#clear').click(function () {
        $('#sr_serial').val('');
        $('#display_nfcread').replaceWith('<div id="display_nfcread"> </div>');
    });

    $(".page-link").click(function() {
        var page= $(this).closest("li").attr("id");
        $("#display_search").load("../backend/display_company_search.php?page="+page, $("#fwd_lookup_form").serializeArray());
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
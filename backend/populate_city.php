<?php 

if(isset($_POST['province'])){
    include("../backend/conn_address.php");

    $province = $mysqli->real_escape_string($_POST['province']);
    $qry = "SELECT c.`citymunDesc` `city` FROM city c
            INNER JOIN province p on p.provCode = c. provCode
            WHERE p.`provDesc`='$province'
            ORDER BY `city` ASC;";
    $result_city = $mysqli->query($qry);
    echo "<option>-</option>";  
    while($cities = mysqli_fetch_assoc($result_city)) {
        $city = strtolower($cities['city']);
        $city = ucwords($city);
        echo "<option value='$city'>$city</option>";  
    }
}
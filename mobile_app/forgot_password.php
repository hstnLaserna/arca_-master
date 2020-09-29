<?php
    session_start();
    include ( "conn.php" );
    $oscaID = $_POST["oscaID"];

    $qry="call `fetch_password`('$oscaID')";
    // $qry="call `fetch_password`(20200001)";

    $result=mysqli_query($conn, $qry);
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) == 1){
        echo json_encode($row);
    }else{
        echo "Account does not exist";
    }
?>

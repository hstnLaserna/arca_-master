<?php
    session_start();
    include ( "conn.php" );
    $oscaID = $_POST["oscaID"];
    $password = $_POST["password"];

    $qry="call `login_member`('$oscaID', '$password')";
    //$qry="call `login_member`('0421-2000003', '0421-2000003')";
    $result=mysqli_query($conn, $qry);
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) == 1){
        $picture = "../resources/members/".$row["picture"];
        echo json_encode($row);
    }else{
        echo "Account does not exist";
    }
?>

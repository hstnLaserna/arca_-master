<?php
      session_start();
      include ( "conn.php" );
      $oscaID = $_POST["oscaID"];

    //   $qry = "call `fetch_pharma_transactions`(20200001)";
      $qry= "call `fetch_pharma_transactions`('$oscaID')";
      
      $result = mysqli_query($conn, $qry);
      $json_array = array();

      while($row = mysqli_fetch_assoc($result)){
          $json_array[] = $row;
      }

      echo json_encode($json_array);
 ?>

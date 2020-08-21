<?php
  include('../frontend/header.php');
?>
    <div class="user-dashboard">
        <?php
            if (!isset($_GET['page'])) {
                include "home.php";
            } else {
            switch ($_GET['page']) {
                case "home":
                    include "home.php";
                    break;
                case "scan":
                    include "scan.php";
                    break;
                default:
                    include "home.php";
                };
            }
        ?>
    </div>
<?php
  include('../frontend/foot.php');
?>
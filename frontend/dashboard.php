<?php
  include('../frontend/head.php');
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
            case "search":
                include "search.php";
                break;
            case "members":
                include "members.php";
                break;
            case "management":
                include "management.php";
                break;
            case "user_profile":
                include "user_profile.php";
                break;
            case "member_profile":
                include "member_profile.php";
                break;
            case "edit_member":
                include "edit_member.php";
                break;
            case "edit_admin":
                include "edit_admin2.php";
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

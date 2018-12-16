<div style="padding-top: 20px" ><br>

    <?php

    if ($_GET["page"] == "user/user_index") {
        if ($_GET["action"] == "delete") {
            include "user_delete.php";
        } else if ($_GET["action"] == "update") {
            include "user_update.php";
        } else if ($_GET["action"] == "create") {
            include "user_add.php";
        } else {
            include "user_read_all.php";
        }
    } else {
        include "user_read_all.php";
    }
    ?>

</div>
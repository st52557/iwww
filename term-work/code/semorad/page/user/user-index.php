<div id="userss" ><br>

    <?php

    if ($_GET["page"] == "user/user-index") {
        if ($_GET["action"] == "delete") {
            include "user-delete.php";
        } else if ($_GET["action"] == "update") {
            include "user-update.php";
        } else if ($_GET["action"] == "create") {
            include "user-add.php";
        } else {
            include "user-read-all.php";
        }
    } else {
        include "user-read-all.php";
    }
    ?>

</div>
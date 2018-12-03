<div id="userss" ><br>

    <?php

    if ($_GET["page"] == "auta/auta_index") {
        if ($_GET["action"] == "delete") {
            include "auta_delete.php";
        } else if ($_GET["action"] == "update") {
            include "auta_update.php";
        } else if ($_GET["action"] == "create") {
            include "auta_add.php";
        } else {
            include "auta_add.php";
        }
    } else {
        include "auta_add.php";
    }
    ?>

</div>
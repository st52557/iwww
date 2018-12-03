<h1>Log out</h1>

<?php
    session_destroy();
    header("location:" . BASE_URL);
?>
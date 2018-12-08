<?php
include "page/config.php";
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">


    <link rel="stylesheet" type="text/css"
          href="styles.css">
    <title>LukasServis</title>
</head>
<body>
<header>

        <ul>
            <li class="navTlacitka"><a href="<?=BASE_URL . "?page=default" ?>">Domů</a></li>
            <li class="navTlacitka">  <a href="<?=BASE_URL . "?page=galerie" ?>">Galerie</a></li>
            <li class="navTlacitka">  <a href="<?=BASE_URL . "?page=kontakt" ?>">Kontakt</a></li>
            <li >
                <a id="loged">
                <div id="loginMsg" >
                    <?php echo $_SESSION["LoginMsg"]; ?>
                </div>
                </a>
            </li>

            <?php if (!empty($_SESSION["user_id"])) { ?>
            <li class="nav_right">   <a href="<?=BASE_URL . "?page=user/logout" ?>">Odhlásit</a></li>
            <li class="nav_right">   <a href="<?= BASE_URL . "?page=auta/auta_add" ?>">Moje Auta</a></li>
            <li class="nav_right">   <a href="<?=BASE_URL . "?page=zmena_hesla" ?>">Změna hesla</a></li>
            <li class="nav_right">   <a href="<?=BASE_URL . "?page=faktury/faktury_add" ?>">Faktury</a></li>
                <li class="nav_right">   <a href="<?=BASE_URL . "?page=predani_auta/predani_all" ?>">Předání auta</a></li>

            <?php  if (($_SESSION["user_role"])=='a'){ ?>
            <li class="nav_right">   <a href="<?= BASE_URL . "?page=user/user-index" ?>">Uživatelé</a></li>

            <?php }} else { ?>
            <li class="nav_right" onclick="document.getElementById('modal-wrapper').style.display='block'">
                <a >Přihlásit se</a></li>

            <li class="nav_right">  <a href="<?= BASE_URL . "?page=user/user-add&action=create" ?>">Registrovat</a></li>
            <?php } ?>

        </ul>

</header>


<?php
$file = "./page/" . $_GET['page'] . ".php";

if( file_exists($file)) {
    include $file;
}else {
    include "./page/default.php";
}
?>

<!-- https://www.youtube.com/watch?v=yafNKsqZ4cA -->
<div id="modal-wrapper" class="modal">

    <form class="modal-content animate" method="POST" action="page/user/login.php">

        <div class="imgcontainer">
            <span onclick="document.getElementById('modal-wrapper').style.display='none'" class="close" title="Close PopUp">&times;</span>

            <h1 style="text-align:center">Přihlášení</h1>
        </div>

        <div class="container">
            <input type="text" placeholder="Vložte Email" name="loginMail">
            <input type="password" placeholder="Vložte heslo" name="loginPassword">
            <button type="submit">Login</button>
        </div>

    </form>

</div>




<script>
var modal = document.getElementById('modal-wrapper');
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>



<footer>
    <div class="full-width-wrapper">
        <div class="flex-wrap">
            <section class="card">
                <h4>Užitečné odkazy</h4>
                <ul>
                    <li><a href="#">Naši dodavatelé</a> </li>
                    <li><a href="#">Spolupráce</a> </li>
                    <li><a href="#">Vybavení</a> </li>
                    <li><a href="#">Autorizace</a> </li>
                    <li><a href="#">O nás</a> </li>
                </ul>
            </section>

            <section  class="card">
                <h4>Kontakt</h4>
                <address>
                    Lukášovo, s. r. o.<br>
                    1997 Vyskytná <br>
                    CZ<br>
                    +420 123 456 789<br>
                    E-mail: <a href="mailto: st52557@student.upce.cz">
                    st52557@student.upce.cz</a><br>
                </address>
            </section>


        </div>
        <section style="margin: auto" class="card">
            <p>
                Copyleft
                <?= date ("Y",strtotime("-1 year")); ?>
                –
                <?php echo date("Y"); ?>
                <a href="https://github.com">
                    Lukáš
                </a>
            </p>
        </section>

    </div>

</footer>
</body>
</html>
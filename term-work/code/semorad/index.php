<?php
include "page/config.php";
ob_start();
session_start();

$message = "";
if (isset($_POST['newsletter'])) {
if (!empty($_POST['email'])) {
if ((preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $_POST['email']))) {
try {
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// prepare sql and bind parameters
$stmt = $conn->prepare("INSERT INTO newsletter (email, created) VALUES (:email, NOW())");
$stmt->bindParam(':email', $_POST["email"]);
$stmt->execute();

$message = "Your are subscribed!";
} catch (PDOException $e) {
echo "Error: " . $e->getMessage();
$message = "Unable to save to the database!";
}
} else {
$message = "Bad formatted email address!";
}
} else {
$message = "Email address is needed!";
}
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" type="text/css"
          href="styles.css">
    <title>Lukášovo</title>
</head>
<body>
<header>

        <ul>
            <li><a href="<?=BASE_URL . "?page=default" ?>">Domů</a></li>
            <li>  <a href="<?=BASE_URL . "?page=galerie" ?>">Galerie</a></li>
            <li>  <a href="<?=BASE_URL . "?page=kontakt" ?>">Kontakt</a></li>

            <?php if (!empty($_SESSION["user_id"])) { ?>
            <li class="nav_right">   <a href="<?=BASE_URL . "?page=user/logout" ?>">Odhlásit</a></li>
            <li class="nav_right">   <a href="<?= BASE_URL . "?page=auta/auta_add" ?>">Moje Auta</a></li>
            <li class="nav_right">   <a href="<?=BASE_URL . "?page=zmena_hesla" ?>">Změna hesla</a></li>

            <?php  if (($_SESSION["user_role"])=='a'){ ?>
            <li class="nav_right">   <a href="<?= BASE_URL . "?page=user/user-index" ?>">Databáze</a></li>

            <?php }} else { ?>
            <li class="nav_right" onclick="document.getElementById('modal-wrapper').style.display='block'">
                <a >Přihlásit se</a></li>

            <li class="nav_right">  <a href="<?= BASE_URL . "?page=user/user-add&action=create" ?>">Registrovat</a></li>
            <?php } ?>

        </ul>

</header>
<?php echo $_SESSION["LoginMsg"]; ?>


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
                <h4>About me</h4>
                <ul>
                    <li><a href="#">Work with me</a> </li>
                    <li><a href="#">References</a> </li>
                    <li><a href="#">Contact me</a> </li>
                    <li><a href="#">Authors</a> </li>
                    <li><a href="#">Login</a> </li>
                </ul>
            </section>

            <section  class="card">
                <h4>Contact</h4>
                <address>
                    Lukášovo, s. r. o.<br>
                    1997 Vyskytná <br>
                    CZ<br>
                    +420 123 456 789<br>
                    E-mail: <a href="mailto: st52557@student.upce.cz">
                    st52557@student.upce.cz</a><br>
                </address>
            </section>



            <section id="footer-newsletter" class="card" >
                <h4>Newsletter</h4>

                <form method="POST" action="#">
                    <div>
                        <label>
                            Enter your Email address:
                        </label>
                    </div>

                    <div>
                        <input type="text"
                               name="email"/>
                    </div>
                    <div>
                        <input type="submit"
                               name="newsletter"
                               value="Subscribe"/>
                    </div>
                    <?php
                    if (!empty($message)) {
                        echo $message;
                        $message = "";
                    }
                    ?>
                </form>

            </section>






            <section class="card">
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
    </div>

</footer>
</body>
</html>
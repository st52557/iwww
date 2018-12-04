<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["typ"])) {
        $feedbackMessage = "Typ opravy is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["pred_cena"])) {
        $feedbackMessage = "Předběžná cena is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["id_faktura"])) {
        $feedbackMessage = "Číslo faktury is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["skut_cena"])) {
        $_POST["skut_cena"] = 0;
    }

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE Oprava 
        SET Typ_opravy= :typ, Predbezna_cena= :p_cena, Skutecna_cena = :sk_cena  WHERE ID_Oprava = :id");
        $stmt->bindParam(':id', $_GET["id"]);
        $stmt->bindParam(':typ', $_POST["typ"]);
        $stmt->bindParam(":p_cena", $_POST["pred_cena"]);
        $stmt->bindParam(":sk_cena", $_POST["skut_cena"]);
        $stmt->execute();
        $successFeedback = "Oprava was updated";
    }
}
?>

<?php
if (!empty($errorFeedbacks)) {
    echo "Form contains following errors:<br>";
    foreach ($errorFeedbacks as $errorFeedback) {
        echo $errorFeedback . "<br>";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
    echo $successFeedback;
}
?>

<?php
if (empty($errorFeedbacks)) { //load data origin data from database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM Oprava WHERE ID_Oprava = :id");
    $stmt->bindParam(':id', $_GET["id"]);
    $stmt->execute();
    $oprava = $stmt->fetch();

    $typValue = $oprava["Typ_opravy"];
    $p_cenaValue = $oprava["Predbezna_cena"];
    $sk_cenaValue = $oprava["Skutecna_cena"];
} else { //in case of any error, load data
    $typValue = $_POST["typ"];
    $p_cenaValue = $_POST["pred_cena"];
    $sk_cenaValue = $_POST["skut_cena"];
}
?>

<div class="formular">

    <h2>Úprava opravy</h2>

    <p style="font-size: xx-large;background-color: red">
        <?php echo $errorFeedback ?>
    </p>

    <p style="font-size: xx-large;background-color: lightskyblue">
        <?php echo $successFeedback ?>
    </p>


    <form method="post">
        <input type="hidden" name="id" value="<?= $_GET["id"]; ?>">
        <input type="text" name="typ" placeholder="spz" value="<?= $typValue; ?>"/>
        <input type="text" name="p_cena" placeholder="nazev" value="<?= $p_cenaValue; ?>">
        <input type="text" name="sk_cena" placeholder="nazev" value="<?= $sk_cenaValue; ?>">
        <input type="submit" name="isSubmitted" value="Potvrdit změny">
    </form>

</div>
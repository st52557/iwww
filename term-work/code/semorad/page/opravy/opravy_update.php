<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {




    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE Oprava 
        SET Skutecna_cena = :sk_cena  WHERE ID_Oprava = :id");

        $stmt->bindParam(':id', $_GET["id"]);
        $stmt->bindParam(":sk_cena", $_POST["sk_cena"]);
        $stmt->execute();
        $successFeedback = "Oprava byla upravena";
        //přesměrování zpět na opravy ? header?
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


    $sk_cenaValue = $oprava["Skutecna_cena"];
} else {
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

        <input type="text" name="sk_cena" placeholder="Skutečná cena" value="<?= $sk_cenaValue; ?>">
        <input type="submit" name="isSubmitted" value="Potvrdit změny">
    </form>

</div>
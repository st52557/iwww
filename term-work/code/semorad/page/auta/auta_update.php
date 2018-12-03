<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["spz"])) {
        $feedbackMessage = "spz is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE Auta 
        SET SPZ= :spz, Nazev= :nazev  WHERE ID_Auta = :id");
        $stmt->bindParam(':id', $_POST["id"]);
        $stmt->bindParam(':spz', $_POST["spz"]);
        $stmt->bindParam(':nazev', $_POST["nazev"]);
        $stmt->execute();
        $successFeedback = "Auto was updated";
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
    $stmt = $conn->prepare("SELECT * FROM Auta WHERE ID_Uzivatel = :id");
    $stmt->bindParam(':id', $_GET["id"]);
    $stmt->execute();
    $user = $stmt->fetch();

    $emailValue = $user["email"];
    $passwordValue = $user["password"];
} else { //in case of any error, load data
    $emailValue = $_POST["email"];
    $passwordValue = $_POST["password"];
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $_GET["id"]; ?>">
    <input type="text" name="spz" placeholder="spz" value="<?= $emailValue; ?>"/>
    <input type="text" name="nazev" placeholder="nazev" value="<?= $passwordValue; ?>">
    <input type="submit" name="isSubmitted" value="yes">
</form>
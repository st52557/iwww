<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["email"])) {
        $feedbackMessage = "email is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["password"])) {
        $feedbackMessage = "password is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE Uzivatele 
        SET email= :email, password= :password  WHERE ID_Uzivatel = :id");
        $stmt->bindParam(':id', $_POST["id"]);
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':password', $_POST["password"]);
        $stmt->execute();
        $successFeedback = "User was updated";
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
if (empty($errorFeedbacks)) {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM Uzivatele WHERE ID_Uzivatel = :id");
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

<div class="formular">


<form method="post">
    <input type="hidden" name="id" value="<?= $_GET["id"]; ?>">
    <input type="email" name="email" placeholder="Your email" value="<?= $emailValue; ?>"/>
    <input type="password" name="password" placeholder="Password" value="<?= $passwordValue; ?>">
    <input type="submit" name="isSubmitted" value="Potvrdit">
</form>

</div>
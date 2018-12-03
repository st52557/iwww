<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //todo more validation rules
    if (empty($_POST["username"])) {
        $feedbackMessage = "username is required";
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

        $stmt = $conn->prepare("UPDATE users SET email= :email, username= :username, password= :username, description= :description WHERE id= :id");
        $stmt->bindParam(':id', $_POST["id"]);
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':username', $_POST["username"]);
        $stmt->bindParam(':password', $_POST["password"]);
        $stmt->bindParam(':description', $_POST["description"]);
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
if (empty($errorFeedbacks)) { //load data origin data from database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id= :id");
    $stmt->bindParam(':id', $_GET["id"]);
    $stmt->execute();
    $user = $stmt->fetch();

    $emailValue = $user["email"];
    $usernameValue = $user["username"];
    $passwordValue = $user["password"];
    $descriptionValue = $user["description"];
} else { //in case of any error, load data
    $emailValue = $_POST["email"];
    $usernameValue = $_POST["username"];
    $passwordValue = $_POST["password"];
    $descriptionValue = $_POST["description"];
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $_GET["id"]; ?>">
    <input type="email" name="email" placeholder="Your email" value="<?= $emailValue; ?>"/>
    <input type="text" name="username" placeholder="Your username" value="<?= $usernameValue; ?>">
    <input type="password" name="password" placeholder="Password" value="<?= $passwordValue; ?>">
    <label for="description-textarea">Description:</label>
    <textarea name="description" id="description-textarea"><?= $descriptionValue; ?></textarea>
    <input type="submit" name="isSubmitted" value="yes">
</form>
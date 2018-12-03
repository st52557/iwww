<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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

        $stmt = $conn->prepare("INSERT INTO users (email, username, password, description, created)
    VALUES (:email, :username, :password, :description, NOW())");
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':username', $_POST["username"]);
        $stmt->bindParam(':password', $_POST["password"]);
        $stmt->bindParam(':description', $_POST["description"]);
        $stmt->execute();
        $successFeedback = "User was added";
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

<div class="reg">

    <h1>Registrace</h1>

    <p style="font-size: xx-large;background-color: red">
        <?php echo $errorFeedback ?>
    </p>

    <p style="font-size: xx-large;background-color: lightskyblue">
        <?php echo $successFeedback ?>
    </p>

    <form method="post">
        <input type="email" name="email" placeholder="Your email"/>
        <input type="text" name="username" placeholder="Your username">
        <input type="password" name="password" placeholder="Password">
        <label for="description-textarea">Description:</label>
        <textarea name="description" id="description-textarea"></textarea>
        <input type="submit" name="isSubmitted" value="yes">
    </form>

</div>

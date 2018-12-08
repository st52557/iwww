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

    if ((preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $_POST['email']))) {
    }else{
        $feedbackMessage = "email zadán špatně";
        array_push($errorFeedbacks, $feedbackMessage);
    }


    if (empty($errorFeedbacks)) {
        //success

        $hashedPass = hash('sha512',$_POST["password"]);

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Uzivatele (email, password, vytvoreno)
    VALUES (:email, :password, now())");
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':password', $hashedPass);
        $stmt->execute();
        $successFeedback = "Registrace proběhla úspěšně!";
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

<div class="formular">

    <h1>Registrace</h1>

    <p style="font-size: xx-large;background-color: red">
        <?php echo $errorFeedback ?>
    </p>

    <p style="font-size: xx-large;background-color: lightskyblue">
        <?php echo $successFeedback ?>
    </p>

    <form method="post">
        <input type="email" name="email" placeholder="Váš Email"/>
        <input type="password" name="password" placeholder="Heslo"/>
        <input type="submit" name="isSubmitted" value="Potvrdit"/>
    </form>

</div>

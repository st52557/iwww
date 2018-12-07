<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["old_pass"])) {
        $feedbackMessage = "Stare heslo je požadováno";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["new_pass"])) {
        $feedbackMessage = "Nove heslo je požadováno";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if ($_POST["new_pass"] != $_POST["new_pass2"]){
        $feedbackMessage = "Nová hesla se neschodují";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {

        $id_uzivatele = ($_SESSION["user_id"]);
        $hashedPassOld = hash('sha512',$_POST["old_pass"]);


        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM Uzivatele WHERE ID_Uzivatel =  :id_uzivatele");
        $stmt->bindParam(':id_uzivatele', $id_uzivatele);
        $stmt->execute();

        $heslo = $stmt->fetch();

        if($heslo["password"] != $hashedPassOld){

            $feedbackMessage = "Chybné původní heslo";
            array_push($errorFeedbacks, $feedbackMessage);
        }

    }

    if (empty($errorFeedbacks)) {
        //success

        $hashedPassNew = hash('sha512',$_POST["new_pass"]);


        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("UPDATE Uzivatele 
        SET password= :password  WHERE ID_Uzivatel = :id");
        $stmt->bindParam(':id', $_SESSION["user_id"]);
        $stmt->bindParam(':password', $hashedPassNew);
        $stmt->execute();
        $successFeedback = "Heslo úspěšně změněno!";
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

    <h1>Změna hesla</h1>

    <p style="font-size: xx-large;background-color: red">
        <?php echo $errorFeedback ?>
    </p>

    <p style="font-size: xx-large;background-color: lightskyblue">
        <?php echo $successFeedback ?>
    </p>

    <form method="post">

        <input type="password" name="old_pass" placeholder="Staré heslo"  ">
        <input type="password" name="new_pass" placeholder="Nové heslo"  ">
        <input type="password" name="new_pass2" placeholder="Nové heslo znovu"  ">

        <input type="submit" name="isSubmitted" value="Potvrdit">
    </form>

</div>
<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["spz"])) {
        $feedbackMessage = "SPZ is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["email"])) {
        $feedbackMessage = "email is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }


    if (empty($errorFeedbacks)) {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT ID_Uzivatel FROM Uzivatele WHERE email = :email");
        $stmt->bindParam(':email', $_POST["email"]);

        $stmt->execute();

        $id_majitele = $stmt;
 //   echo "ID majitele: " + $id_majitele;
        if(empty($id_majitele)){
            $feedbackMessage = "ID podle emailu nenalezeno";
            array_push($errorFeedbacks, $feedbackMessage);
        }

    }

    if (empty($errorFeedbacks)) {

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Auta (Spz, Nazev, ID_Uzivatele)
    VALUES (:spz, :nazev, (SELECT ID_Uzivatel FROM Uzivatele WHERE email = :email))");

        $stmt->bindParam(':spz', $_POST["spz"]);
        $stmt->bindParam(':nazev', $_POST["nazev"]);
        $stmt->bindParam(":email", $_POST["email"]);

        $stmt->execute();
        $successFeedback = "Auto was added";
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

<?php  if (($_SESSION["user_role"])=='a'){ ?>

    <div class="formular">

        <h1>Přidání nového auta: </h1>

        <p style="font-size: xx-large;background-color: red">
            <?php echo $errorFeedback ?>
        </p>

        <p style="font-size: xx-large;background-color: lightskyblue">
            <?php echo $successFeedback ?>
        </p>

        <form method="post">
            <input type="text" name="spz" placeholder="spz"/>
            <input type="text" name="nazev" placeholder="Nazev">
            <input type="text" name="email" placeholder="E-mail majitele">
            <input type="submit" name="isSubmitted" value="yes">
        </form>

    </div>

<?php } ?>


<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mojeId = ($_SESSION["user_id"]);
$mojeRole = ($_SESSION["user_role"]);
if ($mojeRole == 'a'){

    $stmt = $conn->prepare("SELECT * FROM Auta");
    $stmt->bindParam(":id_user", $mojeId);
    $stmt->execute();

} else {
    $stmt = $conn->prepare("SELECT * FROM Auta WHERE autoservis.Auta.ID_Uzivatele = :id_user");
    $stmt->bindParam(":id_user", $mojeId);
    $stmt->execute();
}


echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>Id</th>
    <th>SPZ</th> 
    <th>Nazev</th>
    <th>Majitel</th>
    <th>Actions</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Auto"] . '</td>
    <td >' . $row["Spz"] . '</td >
    <td >' . $row["Nazev"] . '</td > 
    <td >' . $row["ID_Uzivatele"] . '</td > 
    <td>
        <a href="?page=auta/auta_info&id='.$row["ID_Auto"].'">Podrobnosti</a>
        <a href="?page=auta/auta_index&action=update&id='.$row["ID_Auto"].'">U</a>
        <a href="?page=auta/auta-index&action=delete&id='.$row["ID_Auto"].'">D</a>

    </td>
  </tr >';

}

echo '</table>';
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

        if(empty($id_majitele)){
            $feedbackMessage = "ID podle emailu nenalezeno";
            array_push($errorFeedbacks, $feedbackMessage);
        }

    }

    if (empty($errorFeedbacks)) {   //přidání auta

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
            <input type="submit" name="isSubmitted" value="Přidat auto">
        </form>

    </div>

<?php } ?>

<?php
//crud tabulka

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mojeId = ($_SESSION["user_id"]);
$mojeRole = ($_SESSION["user_role"]);
if ($mojeRole == 'a'){

    $stmt = $conn->prepare("SELECT * FROM Auta JOIN autoservis.Uzivatele 
    ON(autoservis.Auta.ID_Uzivatele = autoservis.Uzivatele.ID_Uzivatel) ");
    $stmt->bindParam(":id_user", $mojeId);
    $stmt->execute();

} else {
    $stmt = $conn->prepare("SELECT * FROM Auta JOIN autoservis.Uzivatele 
    ON(autoservis.Auta.ID_Uzivatele = autoservis.Uzivatele.ID_Uzivatel) 
    WHERE autoservis.Auta.ID_Uzivatele = :id_user");
    $stmt->bindParam(":id_user", $mojeId);
    $stmt->execute();
}


echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>ID</th>
    <th>SPZ</th> 
    <th>Nazev</th>
    <th>Majitel</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Auto"] . '</td>
    <td >' . $row["Spz"] . '</td >
    <td >' . $row["Nazev"] . '</td > 
    <td >' . $row["Email"] . '</td > 
    <td>
        <a href="?page=opravy/opravy_add&id='.$row["ID_Auto"].'&spz='.$row["Spz"].'">Podrobnosti</a>
        
        ';
    if ($mojeRole == 'a'){ ?>
        <a href="?page=auta/auta_index&action=update&id='.$row["ID_Auto"].'">Upravit</a>
        <a href="?page=auta/auta_index&action=delete&id='.$row["ID_Auto"].'">Odstranit</a>
<?php }  echo '
        
        
    </td>
  </tr >';

}

echo '</table>';

if ($mojeRole == 'a'){ ?>
<button id="jsonExport" onclick="location.href = 'page/auta/auta_json_helper.php'">Export - json</button>
<?php } ?>
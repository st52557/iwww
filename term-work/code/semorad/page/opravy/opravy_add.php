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

        $stav = 2;

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Oprava (Typ_opravy, Predbezna_cena, Skutecna_cena, ID_Auto, ID_Faktura, ID_Stav_opravy)
    VALUES (:typ, :p_cena, :sk_cena, :id, :id_fakt, :id_stav)");

        $stmt->bindParam(':id', $_GET["id"]);
        $stmt->bindParam(':typ', $_POST["typ"]);
        $stmt->bindParam(":p_cena", $_POST["pred_cena"]);
        $stmt->bindParam(":sk_cena", $_POST["skut_cena"]);
        $stmt->bindParam(":id_fakt", $_POST["id_faktura"]);
        $stmt->bindParam(":id_stav", $stav);
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

        <h1>Přidání nové opravy: </h1>

        <p style="font-size: xx-large;background-color: red">
            <?php echo $errorFeedback ?>
        </p>

        <p style="font-size: xx-large;background-color: lightskyblue">
            <?php echo $successFeedback ?>
        </p>

        <form method="post">
            <input type="text" name="typ" placeholder="Typ opravy"/>
            <input type="text" name="pred_cena" placeholder="Predběžná cena">
            <input type="text" name="skut_cena" placeholder="Skutečná cena">
            <input type="text" name="id_faktura" placeholder="Cislo faktury">

            <input type="submit" name="isSubmitted" value="Přidat opravu">
        </form>

    </div>

<?php } ?>




<h2 style="text-align: center">Opravy u auta <?php echo $_GET["spz"] ?></h2>

<?php

if(isset($_POST["submit"]) or isset($_POST["submitFalse"])) {

    if(isset($_POST["submit"])){
        $stav = 2;  //schváleno
    }else {
        $stav = 4;  //zamítnuto
    }

    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE Oprava SET ID_Stav_Opravy=:stav WHERE ID_Oprava=:id");

    $stmt->bindParam(":id", $_POST["id_opravy"]);
    $stmt->bindParam(":stav", $stav);

    $stmt->execute();
}

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mojeRole = ($_SESSION["user_role"]);


    $stmt = $conn->prepare("SELECT * FROM Oprava JOIN stav_opravy ON 
 autoservis.Oprava.ID_Stav_opravy = autoservis.stav_opravy.ID_Stav_opravy
 WHERE autoservis.Oprava.ID_Auto = :id_auta");

    $stmt->bindParam(":id_auta", $_GET["id"]);
    $stmt->execute();


echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>Č. faktury</th>
    <th>Typ Opravy</th>
    <th>Předběžná cena</th> 
    <th>Skutečná cena</th>
    <th>Stav</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Faktura"] . '</td>
    <td >' . $row["Typ_opravy"] . '</td>
    <td >' . $row["Predbezna_cena"] . '</td >
    <td >' . $row["Skutecna_cena"] . '</td > 
    <td >' . $row["Typ_stavu"] . '</td > 
    <td>
        
        
        ';
    ?>


    <form method="post">

    <input type="hidden" name="id_opravy" value="<?php echo $row["ID_Oprava"] ?>">
    <input type="submit" name="submit" value="Schválit">
    <input type="submit" name="submitFalse" value="Zamítnout">
    </form>
    <?php

        echo '
            
        ';  if ($mojeRole == 'a'){
            echo '
        <a href="?page=opravy/opravy_update&id='.$row["ID_Auto"].'">Upravit (A)</a>
        
        ';
         }

         echo '
    </td>
  </tr >';

}

echo '</table>';

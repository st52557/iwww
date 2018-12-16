<?php
$errorFeedbacks = array();
$successFeedback = "";

if(isset($_POST["isSubmitted"])){

    if (empty($_POST["id_auta"])) {
        $feedbackMessage = "Spz je vyžadována";
        array_push($errorFeedbacks, $feedbackMessage);
    }


    if (empty($errorFeedbacks)) {


        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO autoservis.Predani_auta ( ID_Auta,Komentar, Cas_vystaveni)
    VALUES (:id, :komentar, now())");

        $stmt->bindParam(":id", $_POST["id_auta"]);
        $stmt->bindParam(":komentar", $_POST["komentar"]);
        $stmt->execute();
        $successFeedback = "Predani bylo přidáno";

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



<?php //vybere auta do combo boxu

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT ID_Auto, Spz FROM Auta");

$stmt->execute();

foreach ($stmt as $row) {
    $option .= '<option value = "'.$row['ID_Auto'].'">'.$row['Spz'].'</option>';
}

?>



<?php  if (($_SESSION["user_role"])=='a'){ ?>

    <div class="formular">

        <h1>Přidání nového předání auta: </h1>

        <p style="font-size: xx-large;background-color: red">
            <?php echo $errorFeedback ?>
        </p>

        <p style="font-size: xx-large;background-color: lightskyblue">
            <?php echo $successFeedback ?>
        </p>

        <form method="post">

            <select name="id_auta">
                <option value="" disabled selected hidden>Vyberte SPZ</option>
                <?php echo $option; ?>
            </select>

            <textarea name="komentar" maxlength="500" placeholder="Komentář"></textarea>

            <input type="submit" name="isSubmitted" value="Přidat předání auta"/>
        </form>

    </div>

<?php } ?>


    <h2 style="text-align: center">Předání aut <?php echo $_GET["spz"] ?></h2>

<?php

if(isset($_POST["submitSchvalit"])) {   //potvrzení předání auta vlastníkovi


    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE autoservis.Predani_auta SET Cas_potvrzeni = now() WHERE ID_Predani_auta=:id");

    $stmt->bindParam(":id", $_POST["id_predani"]);

    $stmt->execute();
}

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mojeRole = ($_SESSION["user_role"]);
if ($mojeRole == 'a'){

    $stmt = $conn->prepare("SELECT * FROM autoservis.Predani_auta JOIN autoservis.Auta ON 
 autoservis.Predani_auta.ID_Auta = autoservis.Auta.ID_Auto");


    $stmt->execute();
} else {

    $stmt = $conn->prepare("SELECT * FROM autoservis.Predani_auta JOIN autoservis.Auta ON 
 autoservis.Predani_auta.ID_Auta = autoservis.Auta.ID_Auto
 WHERE autoservis.Auta.ID_Uzivatele = :id");

    $stmt->bindParam(":id", $_SESSION["user_id"]);
    $stmt->execute();
}


echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>SPZ</th>
    <th>Čas vytvoření předání</th>
    <th>Čas potvrzení předání</th>
    <th>Komentář</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["Spz"] . '</td>    
    <td >' . $row["Cas_vystaveni"] . '</td>
    <td >' . $row["Cas_potvrzeni"] . '</td >
     <td >' . $row["Komentar"] . '</td >
    <td>
        
        
            
        ';
            if(!isset($row["Cas_potvrzeni"])) {
                ?>

                <form method="post">
                    <input type="hidden" name="id_predani" value="<?php echo $row["ID_Predani_auta"] ?>">
                    <input type="submit" name="submitSchvalit" value="Potvrdit">
                </form>
                <?php
            }
        echo '
        ';


    echo '
    </td>
  </tr >';

}

echo '</table>';

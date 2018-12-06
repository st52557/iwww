<?php


if(isset($_POST["isSubmitted"])){
    $errorFeedbacks = array();
    $successFeedback = "";

    if (empty($_POST["id_auta"])) {
        $feedbackMessage = "SPZ is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {

        $stav = 1;  //stav faktury - nedokončeno

        echo "----------------------";
        echo $_POST["spz"];

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Faktura (ID_Auto, ID_Stav_faktury) VALUES 
        (:id_auta, :id_stav)");


        $stmt->bindParam(":id_auta", $_POST["id_auta"]);
        $stmt->bindParam(":id_stav", $stav);
        $stmt->execute();
        $successFeedback = "Faktura přidána";

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
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT Spz,ID_Auto FROM autoservis.Auta");

$stmt->execute();

foreach ($stmt as $row) {
    $option .= '<option value = "'.$row['ID_Auto'].'">'.$row['Spz'].'</option>';
}
?>


<?php  if (($_SESSION["user_role"])=='a'){ ?>

    <div class="formular">

        <h1>Přidání nové faktury: </h1>

        <p style="font-size: xx-large;background-color: red">
            <?php echo $errorFeedback ?>
        </p>

        <p style="font-size: xx-large;background-color: lightskyblue">
            <?php echo $successFeedback ?>
        </p>

        <form method="post">


            <select name="id_auta">
                <option value="" disabled selected hidden>Vyberte SPZ auta</option>
                <?php echo $option; ?>
            </select>

            <input type="submit" name="isSubmitted" value="Přidat fakturu">
        </form>

    </div>

<?php } ?>




    <h2 style="text-align: center">Faktury uživatele <?php echo $_SESSION["user_email"] ?></h2>

<?php

if(isset($_POST["submitDone"]) or isset($_POST["submitVystavit"]) or isset($_POST["submitFalse"])) {

    if(isset($_POST["submitVystavit"])){
        $stav = 2;  //faktura vystavena
    }else if(isset($_POST["submitDone"])) {
        $stav = 3;  //zaplacena
    } else {
        $stav = 4;  //storno
    }

    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE autoservis.Faktura SET ID_Stav_faktury = :stav WHERE ID_Faktura = :id");

    $stmt->bindParam(":id", $_POST["id_faktury"]);
    $stmt->bindParam(":stav", $stav);

    $stmt->execute();
}




$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$mojeRole = ($_SESSION["user_role"]);
if ($mojeRole == 'a'){


    $stmt = $conn->prepare("SELECT * FROM Faktura  JOIN autoservis.Stav_faktury ON 
 autoservis.Faktura.ID_Stav_faktury = autoservis.Stav_faktury.ID_Stav_faktury  JOIN Auta ON 
 autoservis.Faktura.ID_Auto = autoservis.Auta.ID_Auto JOIN Uzivatele ON 
 autoservis.Auta.ID_Uzivatele = autoservis.Uzivatele.ID_Uzivatel 
 ");

    $stmt->bindParam(":id", $_SESSION["user_id"]);
    $stmt->execute();

} else {

    $stmt = $conn->prepare("SELECT * FROM Faktura  JOIN autoservis.Stav_faktury ON 
 autoservis.Faktura.ID_Stav_faktury = autoservis.Stav_faktury.ID_Stav_faktury  JOIN Auta ON 
 autoservis.Faktura.ID_Auto = autoservis.Auta.ID_Auto JOIN Uzivatele ON 
 autoservis.Auta.ID_Uzivatele = autoservis.Uzivatele.ID_Uzivatel 
 WHERE autoservis.Uzivatele.ID_Uzivatel = :id");

    $stmt->bindParam(":id", $_SESSION["user_id"]);
    $stmt->execute();
}





echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>Č. faktury</th>
    <th>Stav</th>
    <th>SPZ</th> 
    <th>ID uživatele</th>
    <th>Email</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Faktura"] . '</td>
    <td >' . $row["Stav_faktury"] . '</td>
    <td >' . $row["Spz"] . '</td >
    <td >' . $row["ID_Uzivatel"] . '</td > 
    <td >' . $row["email"] . '</td > 
    <td>
        
         <a href="?page=faktury/faktura_info&id_faktury='.$row["ID_Faktura"].'">Podrobnosti</a>
        
        ';

 if ($mojeRole == 'a'){
        echo '
        <a href="?page=opravy/opravy_update&id='.$row["ID_Oprava"].'">Upravit (A)</a>
        
          ';
        ?>

     <form method="post">

         <input type="hidden" name="id_faktury" value="<?php echo $row["ID_Faktura"] ?>">
         <input type="submit" name="submitVystavit" value="Vystavit">
         <input type="submit" name="submitDone" value="Zaplaceno">
         <input type="submit" name="submitFalse" value="Storno">
     </form>
        <?php

        echo '
        ';
    }

    echo '
    </td>
  </tr >';

}

echo '</table>';

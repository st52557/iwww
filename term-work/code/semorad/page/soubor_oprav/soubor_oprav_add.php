<?php


if(isset($_POST["isSubmitted"])){
    $errorFeedbacks = array();
    $successFeedback = "";

    if (empty($_POST["id_auta"])) {
        $feedbackMessage = "SPZ is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {



        echo "----------------------";
        echo $_POST["spz"];

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Soubor_oprav (ID_Auto, Vytvoreno) VALUES 
        (:id_auta, now())");


        $stmt->bindParam(":id_auta", $_POST["id_auta"]);
        $stmt->execute();
        $successFeedback = "Soubor_oprav přidána";

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

        <h1>Přidání nového souboru oprav: </h1>

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

            <input type="submit" name="isSubmitted" value="Přidat soubor oprav">
        </form>

    </div>

<?php } ?>




    <h2 style="text-align: center">Soubory oprav uživatele <?php echo $_SESSION["user_email"] ?></h2>

<?php

if(isset($_POST["submitDone"]) or isset($_POST["submitVystavit"]) or isset($_POST["submitFalse"])) {

    if(isset($_POST["submitVystavit"])){
        $stav = "Nezaplaceno";
    }else if(isset($_POST["submitDone"])) {
        $stav = "Zaplaceno";
    } else {
        $stav = "Storno";
    }

    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE autoservis.Soubor_oprav SET Stav_Souboru= :stav WHERE ID_Soubor_oprav = :id");

    $stmt->bindParam(":id", $_POST["ID_Soubor_opray"]);
    $stmt->bindParam(":stav", $stav);

    $stmt->execute();
}




$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$mojeRole = ($_SESSION["user_role"]);
if ($mojeRole == 'a'){


    $stmt = $conn->prepare("SELECT * FROM Soubor_oprav JOIN Auta ON 
 autoservis.Soubor_oprav.ID_Auto = autoservis.Auta.ID_Auto JOIN Uzivatele ON 
 autoservis.Auta.ID_Uzivatele = autoservis.Uzivatele.ID_Uzivatel 
 ");

    $stmt->bindParam(":id", $_SESSION["user_id"]);
    $stmt->execute();

} else {

    $stmt = $conn->prepare("SELECT * FROM Soubor_oprav JOIN Auta ON 
 autoservis.Soubor_oprav.ID_Auto = autoservis.Auta.ID_Auto JOIN Uzivatele ON 
 autoservis.Auta.ID_Uzivatele = autoservis.Uzivatele.ID_Uzivatel 
 WHERE autoservis.Uzivatele.ID_Uzivatel = :id");

    $stmt->bindParam(":id", $_SESSION["user_id"]);
    $stmt->execute();
}





echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>Č. soubor_oprav</th>
    <th>Stav</th>
    <th>SPZ</th> 
    <th>ID uživatele</th>
    <th>Email</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Soubor_oprav"] . '</td>
    <td >' . $row["Stav_Souboru"] . '</td>
    <td >' . $row["Spz"] . '</td >
    <td >' . $row["ID_Uzivatel"] . '</td > 
    <td >' . $row["Email"] . '</td > 
    <td>
        
         <a href="?page=soubor_oprav/Soubor_oprav_info&ID_Soubor_opray='.$row["ID_Soubor_oprav"].'">Podrobnosti</a>
        
        ';

 if ($mojeRole == 'a'){
        echo '
        <a href="?page=opravy/opravy_update&id='.$row["ID_Oprava"].'">Upravit (A)</a>
        
          ';
        ?>

     <form method="post">

         <input type="hidden" name="ID_Soubor_opray" value="<?php echo $row["ID_Soubor_oprav"] ?>">
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

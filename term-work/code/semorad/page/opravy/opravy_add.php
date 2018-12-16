<?php
$errorFeedbacks = array();
$successFeedback = "";

if(isset($_POST["isSubmitted"])){

    if (empty($_POST["typ"])) {
        $feedbackMessage = "Typ opravy is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }



    if (empty($_POST["ID_Soubor_oprav"])) {
        $feedbackMessage = "Číslo soubor_oprav is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["skut_cena"])) {
       $_POST["skut_cena"] = 0;
    }

    if (empty($errorFeedbacks)) {

        $stav = "Neschvaleno";

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Oprava (ID_Typ_opravy, Skutecna_cena, ID_Souboru_oprav, Stav)
    VALUES (:typ, :sk_cena, :ID_Soub_opravy, :stav)");


        $stmt->bindParam(':typ', $_POST["typ"]);
        $stmt->bindParam(":sk_cena", $_POST["skut_cena"]);
        $stmt->bindParam(":ID_Soub_opravy", $_POST["ID_Soubor_oprav"]);
        $stmt->bindParam(":stav", $stav);
        $stmt->execute();
        $successFeedback = "Oprava přidána";

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


<?php //vybere soubor_oprav do combo boxu

$stavFaktury = "Nedokončeno";



$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT ID_Soubor_oprav,ID_Auto FROM autoservis.Soubor_oprav 
                                 WHERE ID_Auto = :id_auta");


$stmt->bindParam(":id_auta", $_GET["id"]);
$stmt->execute();

foreach ($stmt as $row) {
    $option .= '<option value = "'.$row['ID_Soubor_oprav'].'">'.$row['ID_Soubor_oprav'].'</option>';
}

?>

<?php //vybere typy_opravy do combo boxu

$stavFaktury = "Nedokončeno";



$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT ID_Typ_Opravy,Nazev_opravy FROM autoservis.Typ_Opravy");

$stmt->execute();

foreach ($stmt as $row) {

    $optionTyp .= '<option value = "'.$row['ID_Typ_Opravy'].'">'.$row['Nazev_opravy'].'</option>';
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
            <select name="typ">
                <option value="" disabled selected hidden>Vyberte typ opravy</option>
                <?php echo $optionTyp; ?>
            </select>

            <input type="text" name="skut_cena" placeholder="Skutečná cena"/>

            <select name="ID_Soubor_oprav">
                <option value="" disabled selected hidden>Vyberte č. souboru oprav</option>
                <?php echo $option; ?>
            </select>

            <input type="submit" name="isSubmitted" value="Přidat opravu"/>
        </form>

    </div>

<?php } ?>


<h2 style="text-align: center">Opravy u auta <?php echo $_GET["spz"] ?></h2>

<?php

if(isset($_POST["submit"]) or isset($_POST["submitFalse"]) or isset($_POST["submitDone"])) {

    if(isset($_POST["submit"])){
        $stav = "Schváleno";
    }else if(isset($_POST["submitFalse"])) {
        $stav = "Zamítnuto";
    } else {
        $stav = "Dokončeno";
    }

    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE Oprava SET Stav=:stav WHERE ID_Oprava=:id");

    $stmt->bindParam(":id", $_POST["id_opravy"]);
    $stmt->bindParam(":stav", $stav);

    $stmt->execute();
}

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mojeRole = ($_SESSION["user_role"]);


    $stmt = $conn->prepare("SELECT * FROM Oprava JOIN autoservis.Soubor_oprav ON(ID_souboru_oprav = ID_Soubor_oprav)
 JOIN autoservis.Typ_Opravy ON(autoservis.Oprava.ID_Typ_opravy = autoservis.Typ_Opravy.ID_Typ_opravy)
 WHERE ID_Auto = :id_auta");

    $stmt->bindParam(":id_auta", $_GET["id"]);
    $stmt->execute();


echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>Č. souboru oprav</th>
    <th>Typ Opravy</th>
    <th>Předběžná cena</th> 
    <th>Skutečná cena</th>
    <th>Stav</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Soubor_oprav"] . '</td>
    <td >' . $row["Nazev_opravy"] . '</td>
    <td >' . $row["Cena"] . '</td >
    <td >' . $row["Skutecna_cena"] . '</td > 
    <td >' . $row["Stav"] . '</td > 
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
        <a href="?page=opravy/opravy_update&id='.$row["ID_Oprava"].'">Upravit (A)</a>
          ';
    ?>
        <form method="post">
    <input type="hidden" name="id_opravy" value="<?php echo $row["ID_Oprava"] ?>">
    <input type="submit" name="submitDone" value="Dokončit">
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

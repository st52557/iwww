<div id="printElement">
<h2 style="text-align: center" xmlns="">Soubor oprav č. <?php echo $_GET["ID_Soubor_opray"] ?></h2>
    <h3 id="onlyPrint"> Vytvořeno pro <?php echo $_SESSION["user_email"] ?></h3>

<?php

$cenaCelkem = 0;

if(isset($_POST["submit"]) or isset($_POST["submitFalse"]) or isset($_POST["submitDone"])) {

    if(isset($_POST["submit"])){
        $stav = "Schválené";
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


$stmt = $conn->prepare("SELECT * FROM Oprava JOIN Typ_Opravy USING(ID_Typ_opravy)
 WHERE autoservis.Oprava.ID_Souboru_oprav = :id_souboru");

$stmt->bindParam(":id_souboru", $_GET["ID_Soubor_opray"]);
$stmt->execute();


echo '<table class="tabulka_crud" style=" margin-bottom: 30px;
">';

echo '  
  <tr>
    <th>ID opravy</th>
    <th>Typ Opravy</th>
    <th>Předběžná cena</th> 
    <th>Skutečná cena</th>
    <th>Stav</th>
  </tr>';

foreach ($stmt as $row) {
    if($row["Stav"] == "Schváleno") {
        $cenaCelkem = $cenaCelkem + $row["Skutecna_cena"];
    }
    echo '   
   <tr >
    <td >' . $row["ID_Oprava"] . '</td>
    <td >' . $row["Nazev_opravy"] . '</td>
    <td >' . $row["Cena"] . '</td >
    <td >' . $row["Skutecna_cena"] . '</td > 
    <td >' . $row["Stav"] . '</td > 


  </tr >';

}

echo '</table>';

?>
<script>
    function tisk() {
    window.print();
    }
</script>

<div>

    <h2 style="text-align: center;margin-bottom: 100px;">Celková cena (schválené položky):  <?php echo $cenaCelkem ?> Kč.</h2>
    <button id="tiskBtn" onclick="tisk()">Vytisknout</button>

</div>

</div>
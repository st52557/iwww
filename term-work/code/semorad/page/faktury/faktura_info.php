<div id="printElement">
<h2 style="text-align: center" xmlns="">Faktura č. <?php echo $_GET["id_faktury"] ?></h2>
    <h3 id="onlyPrint"> Vystaveno pro <?php echo $_SESSION["user_email"] ?></h3>

<?php

$cenaCelkem = 0;

if(isset($_POST["submit"]) or isset($_POST["submitFalse"]) or isset($_POST["submitDone"])) {

    if(isset($_POST["submit"])){
        $stav = 2;  //schváleno
    }else if(isset($_POST["submitFalse"])) {
        $stav = 4;  //zamítnuto
    } else {
        $stav = 3;  //dokonceno (admin)
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
 WHERE autoservis.Oprava.ID_Faktura = :id_faktury AND autoservis.Oprava.ID_Stav_opravy != 4");

$stmt->bindParam(":id_faktury", $_GET["id_faktury"]);
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
$cenaCelkem = $cenaCelkem + $row["Skutecna_cena"];
    echo '   
   <tr >
    <td >' . $row["ID_Oprava"] . '</td>
    <td >' . $row["Typ_opravy"] . '</td>
    <td >' . $row["Predbezna_cena"] . '</td >
    <td >' . $row["Skutecna_cena"] . '</td > 
    <td >' . $row["Typ_stavu"] . '</td > 


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

    <h2 style="text-align: center;margin-bottom: 100px;">Celková cena:  <?php echo $cenaCelkem ?> Kč.</h2>
    <button id="tiskBtn" onclick="tisk()">Vytisknout</button>

</div>

</div>
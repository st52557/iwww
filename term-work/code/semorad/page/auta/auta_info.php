<h1>Info o Autu</h1>
<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mojeRole = ($_SESSION["user_role"]);


    $stmt = $conn->prepare("SELECT * FROM Oprava WHERE autoservis.Oprava.ID_Auto = :id_auta");
    $stmt->bindParam(":id_auta", $_GET["id"]);
    $stmt->execute();


echo '<table class="tabulka_crud">';

echo '  
  <tr>
    <th>Typ Opravy</th>
    <th>Předběžná cena</th> 
    <th>Schváleno</th>
    <th>Skutečná cena</th>
    <th>Stav</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
    <td >' . $row["Typ_opravy"] . '</td>
    <td >' . $row["Predbezna_cena"] . '</td >
    <td >' . $row["Schvaleno"] . '</td > 
    <td >' . $row["Skutecna_cena"] . '</td > 
    <td >' . $row["Stav"] . '</td > 
    <td>
        <a href="?page=auta/auta_index&action=update&id='.$row["ID_Auto"].'">Schválit</a>
        <?php if ($mojeRole == \'a\'){ ?>
        <a href="?page=auta/auta-index&action=delete&id='.$row["ID_Auto"].'">Upravit (A)</a>
        <?php } ?>
    </td>
  </tr >';

}

echo '</table>';
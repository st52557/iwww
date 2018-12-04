<h2 style="text-align: center">Info o autu <?php echo $_GET["spz"] ?></h2>

<?php

echo $_POST["id_opravy"];

if(isset($_POST["submit"])) {

    $stav = 2;

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
    <td >' . $row["Typ_stavu"] . '</td > 
    <td>
        
        
        ';

    echo $row["ID_Oprava"];
    echo $_POST["id_opravy"];

    ?>


    <form method="post">

    <input type="hidden" name="id_opravy" value="<?php $row["ID_Oprava"] ?>">
    <input type="submit" name="submit" >
    </form>
    <?php



        echo '
        
        
        
        <?php if ($mojeRole == \'a\'){ ?>
        <a href="?page=auta/auta-index&action=delete&id='.$row["ID_Auto"].'">Upravit (A)</a>
        <?php } ?>
    </td>
  </tr >';

}

echo '</table>';

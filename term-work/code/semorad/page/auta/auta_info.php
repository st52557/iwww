<h2 style="text-align: center">Info o autu <?php echo $_GET["spz"] ?></h2>

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
    <th>Typ Opravy</th>
    <th>Předběžná cena</th> 
    <th>Skutečná cena</th>
    <th>Stav</th>
    <th>Akce</th>
  </tr>';

foreach ($stmt as $row) {

    echo '   
   <tr >
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
        <a href="?page=auta/auta-index&action=delete&id='.$row["ID_Auto"].'">Upravit (A)</a>
        
        ';
         }

         echo '
    </td>
  </tr >';

}

echo '</table>';

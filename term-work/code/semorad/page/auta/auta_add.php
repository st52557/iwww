<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["spz"])) {
        $feedbackMessage = "SPZ is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }


    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO Auta (Spz, Nazev)
    VALUES (:spz, :nazev)");
        $stmt->bindParam(':spz', $_POST["spz"]);
        $stmt->bindParam(':nazev', $_POST["nazev"]);
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

    <div class="reg">

        <h1>Přidání nového auta: </h1>

        <p style="font-size: xx-large;background-color: red">
            <?php echo $errorFeedback ?>
        </p>

        <p style="font-size: xx-large;background-color: lightskyblue">
            <?php echo $successFeedback ?>
        </p>

        <form method="post">
            <input type="text" name="spz" placeholder="Your email"/>
            <input type="text" name="nazev" placeholder="Password">
            <input type="submit" name="isSubmitted" value="yes">
        </form>

    </div>



<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $conn->query("SELECT * FROM Auta")->fetchAll();
echo '<table style="width:100%" border="1">';

echo '  
  <tr>
    <th>Id</th>
    <th>SPZ</th> 
    <th>Nazev</th>
    <th>Actions</th>
  </tr>';

foreach ($data as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Auto"] . '</td>
    <td >' . $row["Spz"] . '</td >
    <td >' . $row["Nazev"] . '</td > 
    <td>
        <a href="?page=user/user-index&action=update&id='.$row["ID_Uzivatel"].'">U</a>
        <a href="?page=user/user-index&action=delete&id='.$row["ID_Uzivatel"].'">D</a>
    </td>
  </tr >';

}

echo '</table>';
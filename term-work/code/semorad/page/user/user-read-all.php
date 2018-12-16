<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $conn->query("SELECT * FROM Uzivatele")->fetchAll();

echo '<table class="tabulka_crud"';

echo '  
  <tr>
    <th>ID</th>
    <th>Email</th> 
    <th>Vytvořeno</th>
    <th>Role</th>
    <th>Akce</th>
  </tr>';

foreach ($data as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Uzivatel"] . '</td>
    <td >' . $row["Email"] . '</td >
    <td >' . $row["Vytvoreno"] . '</td > 
    <td >' . $row["Role"] . '</td > 
    <td>
        <a href="?page=user/user-index&action=update&id='.$row["ID_Uzivatel"].'">Upravit</a>
        <a href="?page=user/user-index&action=delete&id='.$row["ID_Uzivatel"].'">Smazat</a>
    </td>
  </tr >';

}

echo '</table>';
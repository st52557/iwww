<a href="<?= BASE_URL . "?page=user/user-index&action=create" ?>">Přidat nový</a>

<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $conn->query("SELECT * FROM Uzivatele")->fetchAll();

echo '<table class="tabulka_crud"';

echo '  
  <tr>
    <th>Id</th>
    <th>Email</th> 
    <th>Created</th>
    <th>Role</th>
    <th>Actions</th>
  </tr>';

foreach ($data as $row) {

    echo '   
   <tr >
    <td >' . $row["ID_Uzivatel"] . '</td>
    <td >' . $row["email"] . '</td >
    <td >' . $row["vytvoreno"] . '</td > 
    <td >' . $row["role"] . '</td > 
    <td>
        <a href="?page=user/user-index&action=update&id='.$row["ID_Uzivatel"].'">U</a>
        <a href="?page=user/user-index&action=delete&id='.$row["ID_Uzivatel"].'">D</a>
    </td>
  </tr >';

}

echo '</table>';
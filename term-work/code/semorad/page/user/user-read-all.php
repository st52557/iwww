<a href="<?= BASE_URL . "?page=user/user-index&action=create" ?>">Přidat nový</a>

<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $conn->query("SELECT * FROM users")->fetchAll();
echo '<table style="width:100%" border="1">';

echo '  
  <tr>
    <th>Id</th>
    <th>Email</th> 
    <th>Created</th>
    <th>Actions</th>
  </tr>';

foreach ($data as $row) {

    echo '   
   <tr >
    <td >' . $row["id"] . '</td>
    <td >' . $row["email"] . '</td >
    <td >' . $row["username"] . '</td > 
    <td >' . $row["created"] . '</td >
    <td>
        <a href="?page=user/user-index&action=update&id='.$row["id"].'">U</a>
        <a href="?page=user/user-index&action=delete&id='.$row["id"].'">D</a>
    </td>
  </tr >';

}

echo '</table>';
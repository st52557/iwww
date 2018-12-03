<?php
session_start();
include "../config.php";
$Message = "";
$ErrMessage = "";

if (!empty($_POST) && !empty($_POST["loginMail"]) && !empty($_POST["loginPassword"])) {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT ID_Uzivatel, email, password, role
                                     FROM Uzivatele WHERE email= :email and password = :password");
    $stmt->bindParam(':email', $_POST["loginMail"]);
    $stmt->bindParam(':password', $_POST["loginPassword"]);
    $stmt->execute();

    $user = $stmt->fetch();
    if (!$user) {
        $ErrMessage = "Uživatel nenalezen!";
    } else {
        $Message = "Jste přihlášen, Vaše ID: " . $user["ID_Uzivatel"];
        $_SESSION["user_id"] = $user["ID_Uzivatel"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_role"] = $user["role"];
    }
} else if (!empty($_POST)) {
    $ErrMessage  = "Prosím vyplňte všechna pole";

} else {
    $ErrMessage  = "Prázdné";
}
if (!(empty($ErrMessage))) {$_SESSION["LoginMsg"] = $ErrMessage;} else {$_SESSION["LoginMsg"] = $Message;}
header("Location:" . BASE_URL);

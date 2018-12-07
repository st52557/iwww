<?php
include "../config.php";



        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM Auta");

        $stmt->execute();

        $json = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $json[] = $row;
        }

         $jsonEncode = json_encode($json);

        $filename = "json_auta.json";
        $file = fopen($filename, "w") or die ("Chyba při zápisu do souboru");
        fwrite($file, $jsonEncode);

        // http://php.net/manual/en/function.readfile.php
             if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Cache-Control: public');
            header('Content-Transfer-Encoding: binary');

            readfile($filename,BASE_URL . 'json_auta.json');
    exit;
}



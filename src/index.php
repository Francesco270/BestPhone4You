<?php
    require_once("./../credentials/database.php");

    try
	{
        $connection = new PDO("mysql:host=" . BP4Y_DB_HOST . ";dbname=" . BP4Y_DB_NAME . ";charset=utf8",
                                BP4Y_DB_USERNAME,
                                BP4Y_DB_PASSWORD,
                                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                            );
		$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	catch(PDOException $e) 
	{
        echo "Errore fatale: impossibile stabilire una connessione con il DB.<br>";
        echo "I dettagli dell'errore sono riportati qui sotto.<br>";
        var_dump($e);
		die();
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <meta name="theme-color" content="#2D3039">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#DA532C">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#2D3039">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>BestPhone4You</title>
</head>
<body>
    <div id="mainContainer">
        <img src="./assets/images/bp4y-logo.png" alt="BestPhone4You">
        <span>COMING SOON</span>
    </div>
</body>
</html>

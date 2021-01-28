<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    require(BP4Y_APP_ROOT_PATH . "../credentials/database.php");

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
        // DB Connection has failed
        exit;
    }

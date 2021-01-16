<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $chosen_OS = (int) $_SESSION["questions_and_answers"][OS_QUESTION_ORDER_NUMBER];

    $OS_ids_sql_query = "SELECT idSistemaOperativo FROM SISTEMI_OPERATIVI;";
    $OS_ids_rows = $connection->query($OS_ids_sql_query)->fetchAll(PDO::FETCH_ASSOC);

    $OS_ids = array_column($OS_ids_rows, "idSistemaOperativo");
    
    // Check if the chosen OS id is valid
    if(in_array($chosen_OS, $OS_ids))
    {
        $statement->bindValue(':chosen_OS_lower', $chosen_OS, PDO::PARAM_INT);
        $statement->bindValue(':chosen_OS_upper', $chosen_OS, PDO::PARAM_INT);
    }
    else if($chosen_OS == 0)
    {
        $statement->bindValue(':chosen_OS_lower', min($OS_ids), PDO::PARAM_INT);
        $statement->bindValue(':chosen_OS_upper', max($OS_ids), PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }

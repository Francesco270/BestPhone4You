<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $must_have_5g = (int) $_SESSION["questions_and_answers"][_5G_QUESTION_ORDER_NUMBER];

    // Check if the 5G response value is valid (must be 0 or 1)
    if( in_array($must_have_5g, array(0, 1)) )
    {
        $statement->bindValue(':_5g_connectivity_boolean_value', $must_have_5g, PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }
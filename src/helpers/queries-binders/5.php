<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $must_have_expandable_memory = (int) $_SESSION["questions_and_answers"][EXPANDABLE_MEMORY_QUESTION_ORDER_NUMBER];

    // Check if the expandable memory response value is valid (must be 0 or 1)
    if( in_array($must_have_expandable_memory, array(0, 1)) )
    {
        $statement->bindValue(':expandable_memory_boolean_value', $must_have_expandable_memory, PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }
    
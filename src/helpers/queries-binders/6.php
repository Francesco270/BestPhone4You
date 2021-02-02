<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $must_have_large_battery = (int) $_SESSION["questions_and_answers"][LARGE_BATTERY_QUESTION_ORDER_NUMBER];

    // Check if the large battery capacity response value is valid (must be 0 or 1)
    if( in_array($must_have_large_battery, array(0, 1)) )
    {
        if( $must_have_large_battery == 1 )
        {
            $statement->bindValue(':battery_capacity_value', 4000, PDO::PARAM_INT);
        }
        else 
        {
            $statement->bindValue(':battery_capacity_value', 0, PDO::PARAM_INT);
        }
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }
    
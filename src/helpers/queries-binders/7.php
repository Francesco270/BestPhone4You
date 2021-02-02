<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $must_have_wireless_charging = (int) $_SESSION["questions_and_answers"][WIRELESS_CHARGING_QUESTION_ORDER_NUMBER];

    // Check if the wireless charging response value is valid (must be 0 or 1)
    if( in_array($must_have_wireless_charging, array(0, 1)) )
    {
        $statement->bindValue(':wireless_charging_boolean_value', $must_have_wireless_charging, PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }
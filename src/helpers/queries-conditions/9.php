<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $must_have_dualsim = (int) $_SESSION["questions_and_answers"][DUALSIM_QUESTION_ORDER_NUMBER];
    
    // Check if the DualSIM response value is valid (must be 0 or 1)
    if( in_array($must_have_dualsim, array(0, 1)) )
    {
        if($must_have_dualsim == 1)
        {
            $sql_query .= " (SMARTPHONES.dualSIM = 'Sì' OR SMARTPHONES.dualSIM = 'Sì (eSIM)')";
        }
        else
        {
            $sql_query .= " (SMARTPHONES.dualSIM = 'Sì' OR SMARTPHONES.dualSIM = 'Sì (eSIM)' OR SMARTPHONES.dualSIM = 'No')";
        }
    }

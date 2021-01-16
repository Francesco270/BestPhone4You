<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $chosen_display_dimension = (int) $_SESSION["questions_and_answers"][DISPLAY_DIMENSION_QUESTION_ORDER_NUMBER];

    /**
     * RV   | Display Dimensions
     * =====|======================
     * 1    |  4.7 - 5.5
     * 2    |  5.51 - 6.2
     * 3    |  6.21 - 7.6
     */
    $display_dimensions_ranges = array(
        1 => array(4.7, 5.5),
        2 => array(5.51, 6.2),
        3 => array(6.21, 7.6)
    );

    // Check if the display dimension response value is valid
    if( in_array($chosen_display_dimension, array_keys($display_dimensions_ranges)) )
    {
        list($display_dimension_lower, $display_dimension_upper) = $display_dimensions_ranges[$chosen_display_dimension];
    
        $statement->bindValue(':display_dimension_lower', $display_dimension_lower, PDO::PARAM_INT);
        $statement->bindValue(':display_dimension_upper', $display_dimension_upper, PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }

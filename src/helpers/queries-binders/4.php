<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    define("MEMORY_CAPACITY_QUESTION_ORDER_NUMBER", 4);

    $chosen_memory_capacity = (int) $_SESSION["questions_and_answers"][MEMORY_CAPACITY_QUESTION_ORDER_NUMBER];

    /**
     * RV   | Memory Capacity (in GB)
     * =====|======================
     * 1    | 64
     * 2    | 128
     * 3    | 256 and above
     */
    $memory_capacities = array(
        1 => 64,
        2 => 128,
        3 => 256 // and above
    );

    // Check if the memory capacity response value is valid
    if( in_array($chosen_memory_capacity, array_keys($memory_capacities)) )
    {
        $statement->bindValue(':memory_capacity', $memory_capacities[$chosen_memory_capacity], PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }
    